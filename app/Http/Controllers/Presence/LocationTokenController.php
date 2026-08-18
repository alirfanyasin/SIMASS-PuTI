<?php

namespace App\Http\Controllers\Presence;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LocationTokenController extends Controller
{
    /**
     * The token lifetime in seconds (2 minutes).
     */
    private const TOKEN_TTL = 120;

    /**
     * Maximum tolerated distance (meters) between submitted coordinates
     * and the coordinates that were validated when the token was issued.
     * Prevents editing the hidden lat/lng fields after the fact.
     */
    private const COORD_TOLERANCE_METERS = 50;

    /**
     * Issue a signed location token after server-side coordinate validation.
     *
     * The client sends the GPS coordinates obtained from the browser.
     * The server validates them, and — only if they pass — returns a
     * short-lived HMAC token that must be submitted with the attendance form.
     * Without a valid token the check-in / check-out endpoint will reject
     * the request, making all client-side JS manipulation useless.
     */
    public function issue(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['required', 'numeric', 'min:0'],
        ]);

        $lat = (float) $request->input('latitude');
        $lng = (float) $request->input('longitude');
        $accuracy = (float) $request->input('accuracy');

        // --- Server-side coordinate validation ---
        $error = $this->validateCoordinates($lat, $lng, $accuracy);
        if ($error) {
            return response()->json([
                'ok' => false,
                'reason' => $error,
            ], 422);
        }

        // --- Build & sign the token ---
        $nonce = Str::random(32);
        $issuedAt = time();
        $userId = Auth::id();
        $sessionId = $request->session()->getId();

        /**
         * Payload layout (pipe-separated, order matters):
         * user_id | session_id | nonce | issued_at | lat | lng
         *
         * lat/lng are rounded to 5 decimal places before signing so that
         * floating-point string representation differences don't break
         * verification while still locking the coordinates tightly.
         */
        $payload = implode('|', [
            $userId,
            $sessionId,
            $nonce,
            $issuedAt,
            round($lat, 5),
            round($lng, 5),
        ]);

        $signature = hash_hmac('sha256', $payload, config('app.key'));

        // Token = base64(payload) . '.' . signature
        $token = base64_encode($payload).'.'.$signature;

        // --- Store nonce in cache (one-time-use enforcement) ---
        $cacheKey = "loc_token_nonce:{$userId}:{$nonce}";
        Cache::put($cacheKey, true, self::TOKEN_TTL);

        return response()->json([
            'ok' => true,
            'token' => $token,
            'expires_in' => self::TOKEN_TTL,
        ]);
    }

    /**
     * Verify a location token submitted with a check-in / check-out form.
     *
     * Returns null if the token is valid, or an error string if not.
     *
     * @param  string  $token  The token from the form submission.
     * @param  float  $submittedLat  Latitude submitted with the form.
     * @param  float  $submittedLng  Longitude submitted with the form.
     */
    public static function verify(
        Request $request,
        string $token,
        float $submittedLat,
        float $submittedLng,
    ): ?string {
        // --- Split token ---
        $parts = explode('.', $token, 2);
        if (count($parts) !== 2) {
            return 'Token lokasi tidak valid (format salah).';
        }

        [$encodedPayload, $providedSignature] = $parts;

        $payload = base64_decode($encodedPayload, strict: true);
        if ($payload === false) {
            return 'Token lokasi tidak valid (encoding rusak).';
        }

        $segments = explode('|', $payload);
        if (count($segments) !== 6) {
            return 'Token lokasi tidak valid (payload tidak lengkap).';
        }

        [$tokenUserId, $tokenSessionId, $nonce, $issuedAt, $tokenLat, $tokenLng] = $segments;

        // --- Signature verification ---
        $expectedSignature = hash_hmac('sha256', $payload, config('app.key'));
        if (! hash_equals($expectedSignature, $providedSignature)) {
            return 'Token lokasi tidak valid (tanda tangan tidak cocok). GPS palsu atau manipulasi terdeteksi.';
        }

        // --- Ownership check ---
        if ((int) $tokenUserId !== Auth::id()) {
            return 'Token lokasi tidak valid (pengguna tidak cocok).';
        }

        // --- Session binding ---
        if ($tokenSessionId !== $request->session()->getId()) {
            return 'Token lokasi tidak valid (sesi tidak cocok).';
        }

        // --- Expiry check ---
        if ((time() - (int) $issuedAt) > self::TOKEN_TTL) {
            return 'Token lokasi sudah kedaluwarsa. Muat ulang halaman dan coba lagi.';
        }

        // --- One-time-use (nonce consumed) ---
        $cacheKey = "loc_token_nonce:{$tokenUserId}:{$nonce}";
        if (! Cache::has($cacheKey)) {
            return 'Token lokasi sudah digunakan atau tidak ditemukan. Muat ulang halaman.';
        }
        Cache::forget($cacheKey); // consume

        // --- Coordinate binding: submitted coords must match token coords ---
        // Allow up to COORD_TOLERANCE_METERS drift to account for natural GPS movement.
        $tokenLatF = (float) $tokenLat;
        $tokenLngF = (float) $tokenLng;
        $distance = self::haversineMeters($tokenLatF, $tokenLngF, $submittedLat, $submittedLng);
        if ($distance > self::COORD_TOLERANCE_METERS) {
            return 'Koordinat yang dikirim tidak cocok dengan lokasi yang diverifikasi. GPS palsu terdeteksi.';
        }

        return null; // Valid
    }

    /**
     * Server-side coordinate validation (mirrors frontend detectFakeLocation).
     */
    private function validateCoordinates(float $lat, float $lng, float $accuracy): ?string
    {
        // 1. Basic range
        if ($lat < -90 || $lat > 90 || $lng < -180 || $lng > 180) {
            return 'Koordinat GPS di luar jangkauan geografis.';
        }

        // 2. Null island (0,0)
        if ($lat === 0.0 && $lng === 0.0) {
            return 'Koordinat GPS tidak valid (titik nol terdeteksi).';
        }

        // 3. Accuracy impossibly perfect (0 or 1 metre)
        if ($accuracy === 0.0 || $accuracy === 1.0) {
            return 'Akurasi GPS tidak valid. GPS palsu kemungkinan aktif.';
        }

        // 4. Accuracy too perfect for a browser GPS (< 2 m is physically impossible)
        if ($accuracy < 2.0) {
            return 'Akurasi GPS mencurigakan (terlalu sempurna). GPS palsu kemungkinan aktif.';
        }

        // 5. Suspiciously round coordinates (< 4 decimal places)
        $latStr = (string) $lat;
        $lngStr = (string) $lng;
        $latDecimals = strlen(strstr($latStr, '.') ?: '') - 1;
        $lngDecimals = strlen(strstr($lngStr, '.') ?: '') - 1;
        if ($latDecimals < 4 || $lngDecimals < 4) {
            return 'Presisi koordinat GPS mencurigakan. Matikan aplikasi GPS palsu jika ada.';
        }

        // 6. Chrome / browser DevTools preset coordinates
        $devToolsPresets = [
            [51.507351, -0.127758],    // London
            [35.676192, 139.650311],   // Tokyo
            [-22.906847, -43.172897],  // Rio de Janeiro
            [40.714272, -74.005966],   // New York
            [48.856613, 2.352222],     // Paris
            [-33.868820, 151.209296],  // Sydney
        ];
        foreach ($devToolsPresets as [$pLat, $pLng]) {
            if (abs($lat - $pLat) < 0.01 && abs($lng - $pLng) < 0.01) {
                return 'Koordinat GPS tidak valid (Developer Tools Sensors terdeteksi).';
            }
        }

        // 7. Bounding box around office (±2 degrees ≈ ±220 km)
        $officeLat = Setting::where('key', 'office_latitude')->value('value');
        $officeLng = Setting::where('key', 'office_longitude')->value('value');

        if ($officeLat && $officeLng) {
            $buffer = 2.0;
            $minLat = (float) $officeLat - $buffer;
            $maxLat = (float) $officeLat + $buffer;
            $minLng = (float) $officeLng - $buffer;
            $maxLng = (float) $officeLng + $buffer;

            if ($lat < $minLat || $lat > $maxLat || $lng < $minLng || $lng > $maxLng) {
                return 'Lokasi terlalu jauh dari area kantor. GPS palsu mungkin aktif.';
            }
        }

        return null;
    }

    /**
     * Calculate distance in metres between two coordinates (Haversine formula).
     */
    private static function haversineMeters(
        float $lat1, float $lon1,
        float $lat2, float $lon2,
    ): float {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
