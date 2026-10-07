<?php

namespace App\Services;

use App\Models\Ticket;

class LunaAiService
{
    /**
     * Analyze title & description to suggest the most relevant service item.
     */
    public function suggestService(string $text): ?array
    {
        $textLower = strtolower($text);

        $rules = [
            'Wi-Fi Kampus (Eduroam / TelU-Guest)' => ['wifi', 'wi-fi', 'eduroam', 'telu-guest', 'sinyal', 'hotspot', 'ap', 'access point'],
            'Jaringan Kabel LAN Gedung / Ruangan' => ['kabel lan', 'kabel jaringan', 'rj45', 'port lan', 'switch', 'koneksi kabel'],
            'Akun iGracias & Single Sign-On (SSO)' => ['igracias', 'sso', 'login igracias', 'akun kampus', 'reset password', 'lupa password', 'akun terblokir'],
            'Email Kampus & Lisensi Microsoft 365' => ['email', 'm365', 'microsoft 365', 'outlook', 'office 365', 'teams', 'lisensi word'],
            'LMS CeLOE & Perkuliahan Daring' => ['celoe', 'lms', 'elearning', 'e-learning', 'tugas kuliah', 'kuis online'],
            'Komputer Laboratorium / PC Kerja' => ['pc lab', 'komputer', 'monitor', 'keyboard', 'mouse', 'cpu', 'pc kerja'],
            'Proyektor / Perangkat Ruang Kelas' => ['proyektor', 'projector', 'hdmi', 'layar kelas', 'vga', 'remote'],
        ];

        foreach ($rules as $serviceItem => $keywords) {
            foreach ($keywords as $kw) {
                if (str_contains($textLower, $kw)) {
                    // Find category and metadata from Ticket::SERVICES
                    foreach (Ticket::SERVICES as $category => $items) {
                        if (isset($items[$serviceItem])) {
                            return [
                                'category' => $category,
                                'service_item' => $serviceItem,
                                'authority' => $items[$serviceItem]['authority'],
                            ];
                        }
                    }
                }
            }
        }

        return null;
    }

    /**
     * Check if a ticket has ambiguous/incomplete info.
     * If ambiguous, returns a polite clarification message.
     */
    public function evaluateAmbiguity(string $description, ?string $location, string $authority): ?string
    {
        $descTrimmed = trim($description);
        $len = strlen($descTrimmed);

        // 1. Description is too short / generic
        $genericPhrases = ['rusak', 'error', 'mati', 'tidak bisa', 'tolong', 'bantu', 'gangguan', 'problem'];
        $isGeneric = in_array(strtolower($descTrimmed), $genericPhrases) || $len < 20;

        // 2. Physical/local service without location
        $needsLocation = ($authority === 'local' && (empty($location) || strlen(trim($location)) < 3));

        if ($isGeneric && $needsLocation) {
            return "Halo! Saya **Luna AI** dari Helpdesk PuTI. Terima kasih telah melapor. 😊\n\nAgar teknisi PuTI kami dapat segera meluncur dan menangani kendala Anda, mohon bantu melengkapi:\n1. **Lokasi spesifik:** Nama Gedung, Lantai, dan Nomor Ruangan.\n2. **Detail kendala:** Gejala kerusakan atau pesan error yang muncul.\n\nAnda dapat langsung membalas pesan ini di bawah. Terima kasih!";
        }

        if ($needsLocation) {
            return 'Halo! Saya **Luna AI**. Untuk mempercepat penanganan langsung di lokasi, mohon sebutkan nama gedung dan nomor ruangan tempat Anda berada saat ini. Terima kasih! 😊';
        }

        if ($isGeneric) {
            return 'Halo! Saya **Luna AI**. Laporan Anda telah kami terima, namun deskripsi kendala terbilang singkat. Jika ada tangkapan layar (screenshot) atau pesan error tertentu, mohon balas di thread ini agar kami dapat mendiagnosis lebih tepat. Terima kasih!';
        }

        return null;
    }

    /**
     * Interactive Luna AI Chatbot logic for /ticket/luna
     */
    public function chat(string $message): string
    {
        $clean = strtolower(trim($message));

        // Privacy Guardrail
        if (preg_match('/(password|kata sandi|pin saya|rahasia|credential)/i', $clean) && preg_match('/\b[a-zA-Z0-9!@#$%^&*]{6,}\b/', $message)) {
            return '⚠️ **Peringatan Privasi:** Harap tidak menuliskan kata sandi, PIN, atau kredensial rahasia Anda dalam percakapan ini demi keamanan akun Anda. PuTI tidak pernah meminta kata sandi Anda.';
        }

        // Wi-Fi Guidance
        if (str_contains($clean, 'wifi') || str_contains($clean, 'wi-fi') || str_contains($clean, 'eduroam') || str_contains($clean, 'telu-guest')) {
            return "Untuk koneksi Wi-Fi di Kampus Surabaya:\n\n1. **Civitas Akademika:** Hubungkan ke SSID **eduroam** menggunakan akun SSO iGracias lengkap (`username@telkomuniversity.ac.id`).\n2. **Tamu / Pengunjung:** Hubungkan ke SSID **TelU-Guest** lalu lakukan login pada landing page browser.\n\nJika sinyal putus-putus atau tidak terdeteksi di ruangan tertentu, silakan ajukan laporan tiket melalui menu **Pengajuan Tiket** agar teknisi kami dapat mengecek Access Point di lokasi Anda.";
        }

        // iGracias / SSO
        if (str_contains($clean, 'igracias') || str_contains($clean, 'sso') || str_contains($clean, 'lupa password') || str_contains($clean, 'reset password')) {
            return "Untuk kendala akun **iGracias / SSO**:\n\n1. Anda dapat mencoba reset mandiri melalui menu **Lupa Password** pada halaman resmi iGracias Telkom University.\n2. Jika akun terkunci atau nomor telepon/email pemulihan sudah tidak aktif, silakan buat laporan tiket dengan kategori **Akun & Akses Terpadu**. Tim PuTI Surabaya akan mendampingi koordinasi reset akun Anda bersama tim sistem terpusat.";
        }

        // Office 365 / Email
        if (str_contains($clean, 'email') || str_contains($clean, 'office') || str_contains($clean, 'm365')) {
            return 'Setiap mahasiswa dan staf aktif Telkom University mendapatkan akun **Microsoft 365** dan email resmi `@telkomuniversity.ac.id`. Akses dapat dibuka via [portal.office.com](https://portal.office.com). Jika lisensi Anda bermasalah atau kuota penuh, silakan buat tiket bantuan di sistem ini.';
        }

        // Default supportive guidance
        return "Halo! Saya **Luna AI**, asisten digital PuTI Telkom University Surabaya. 😊\n\nSaya dapat membantu memberikan panduan terkait Wi-Fi kampus, akun iGracias, email M365, serta membantu mengarahkan pelaporan kendala Anda.\n\nApakah ada kendala teknis tertentu yang sedang Anda alami hari ini?";
    }
}
