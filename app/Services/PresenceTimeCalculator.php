<?php

namespace App\Services;

use App\Models\Overtime;
use App\Models\Presence;
use App\Models\Setting;
use Carbon\Carbon;

class PresenceTimeCalculator
{
    public const DEFAULT_WORK_START = '08:30:00';

    public const DEFAULT_WORK_END = '16:30:00';

    public const MAX_REGULAR_MINUTES = 480;

    private string $workStart;

    private string $workEnd;

    public function __construct(?string $workStart = null, ?string $workEnd = null)
    {
        $settings = ($workStart === null || $workEnd === null)
            ? Setting::whereIn('key', ['jam_masuk', 'jam_pulang'])->pluck('value', 'key')
            : collect();

        $this->workStart = Carbon::parse($workStart ?? $settings->get('jam_masuk', self::DEFAULT_WORK_START))->format('H:i:s');
        $this->workEnd = Carbon::parse($workEnd ?? $settings->get('jam_pulang', self::DEFAULT_WORK_END))->format('H:i:s');
    }

    public function workStart(): string
    {
        return $this->workStart;
    }

    public function workEnd(): string
    {
        return $this->workEnd;
    }

    public function regularMinutes(Presence $presence): int
    {
        if (! $presence->jam_masuk || ! $presence->jam_pulang) {
            return 0;
        }

        $workEnd = Carbon::parse($presence->tanggal.' '.$this->workEnd);
        $checkIn = Carbon::parse($presence->tanggal.' '.$presence->jam_masuk);
        $checkOut = Carbon::parse($presence->tanggal.' '.$presence->jam_pulang)->min($workEnd);

        if ($checkOut->lessThanOrEqualTo($checkIn)) {
            return 0;
        }

        return min(self::MAX_REGULAR_MINUTES, (int) $checkIn->diffInMinutes($checkOut));
    }

    public function displayMinutes(Presence $presence): int
    {
        $transferredMinutes = $presence->relationLoaded('overtimeTransfers')
            ? (int) $presence->overtimeTransfers->sum('durasi_menit')
            : (int) $presence->overtimeTransfers()->sum('durasi_menit');

        return min(self::MAX_REGULAR_MINUTES, $this->regularMinutes($presence) + $transferredMinutes);
    }

    public function roundedDisplayHours(Presence $presence): int
    {
        return $this->roundMinutesToHours($this->displayMinutes($presence));
    }

    public function roundMinutesToHours(int $minutes): int
    {
        $wholeHours = intdiv($minutes, 60);

        if ($minutes % 60 >= 30) {
            $wholeHours++;
        }

        return min(8, $wholeHours);
    }

    public function displayDuration(Presence $presence): string
    {
        if (! $presence->jam_pulang && ! $presence->relationLoaded('overtimeTransfers')) {
            return '—';
        }

        $hours = $this->roundedDisplayHours($presence);

        return $hours > 0 ? $hours.' Jam' : '—';
    }

    public function earlyOvertimeMinutes(Presence $presence): int
    {
        if (! $presence->jam_masuk) {
            return 0;
        }

        if (! $presence->jam_pulang) {
            return 0;
        }

        $workStart = Carbon::parse($presence->tanggal.' '.$this->workStart);
        $workEnd = Carbon::parse($presence->tanggal.' '.$this->workEnd);
        $checkIn = Carbon::parse($presence->tanggal.' '.$presence->jam_masuk);

        if ($checkIn->greaterThanOrEqualTo($workStart)) {
            return 0;
        }

        $checkOut = Carbon::parse($presence->tanggal.' '.$presence->jam_pulang)->min($workEnd);
        $workedMinutes = $checkOut->greaterThan($checkIn)
            ? (int) $checkIn->diffInMinutes($checkOut)
            : 0;
        $excessMinutes = max(0, $workedMinutes - self::MAX_REGULAR_MINUTES);
        $earlyMinutes = (int) $checkIn->diffInMinutes($workStart);

        return min($earlyMinutes, $excessMinutes);
    }

    public function syncEarlyOvertime(Presence $presence): void
    {
        $overtimeMinutes = $this->earlyOvertimeMinutes($presence);
        $existingOvertime = Overtime::where('presence_id', $presence->id)->first();

        $presence->update(['menit_tambahan' => $overtimeMinutes]);

        if ($overtimeMinutes === 0) {
            if ($existingOvertime && ! $existingOvertime->transfers()->exists()) {
                $existingOvertime->delete();
            }

            return;
        }

        $usedMinutes = $existingOvertime
            ? max(0, $existingOvertime->durasi_menit - $existingOvertime->sisa_menit)
            : 0;

        Overtime::updateOrCreate(
            ['presence_id' => $presence->id],
            [
                'user_id' => $presence->user_id,
                'tanggal' => $presence->tanggal,
                'durasi_menit' => $overtimeMinutes,
                'sisa_menit' => max(0, $overtimeMinutes - $usedMinutes),
                'keterangan' => 'Lembur otomatis (kelebihan kerja di atas 8 jam dari kehadiran sebelum pukul '
                    .substr($this->workStart, 0, 5).')',
            ],
        );
    }
}
