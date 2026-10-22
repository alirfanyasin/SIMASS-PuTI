<?php

namespace Database\Seeders;

use App\Models\PortalModule;
use Illuminate\Database\Seeder;

class PortalModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            [
                'code' => 'admin',
                'name' => 'Admin Console',
                'description' => 'Pusat manajemen hak akses, master data, monitoring SLA, dan konfigurasi portal.',
                'credit' => 'Kontrol Terpusat PuTI Surabaya',
                'icon' => 'sliders-horizontal',
                'route_name' => 'admin.dashboard',
                'status' => 'active',
                'order' => 1,
                'allowed_roles' => ['super-admin'],
            ],
            [
                'code' => 'presensi',
                'name' => 'SIMASS Presensi',
                'description' => 'Sistem absensi berbasis AI Face Recognition & Dynamic Geofencing terenkripsi.',
                'credit' => 'Courtesy to Irfan Yasin S.Kom (Student Staff PuTI Gen. 2023)',
                'icon' => 'user-check',
                'route_name' => 'presence.dashboard',
                'status' => 'active',
                'order' => 2,
                'allowed_roles' => null,
            ],
            [
                'code' => 'eticket',
                'name' => 'e-Ticket PuTI',
                'description' => 'Platform helpdesk, pelaporan insiden, dan permintaan layanan dengan Luna AI.',
                'credit' => 'Courtesy to Reynanda Shaquile S.Kom (TLH PuTI Gen. 2024)',
                'icon' => 'ticket',
                'route_name' => 'ticket.index',
                'status' => 'active',
                'order' => 3,
                'allowed_roles' => null,
            ],
            [
                'code' => 'neing',
                'name' => 'Neing',
                'description' => 'Agen AI percakapan berbasis Telegram & WhatsApp untuk interaksi layanan instan.',
                'credit' => 'Courtesy to Reza Eka Firmansyah S.Kom (TLH PuTI Gen. 2024)',
                'icon' => 'bot',
                'route_name' => null,
                'status' => 'coming_soon',
                'order' => 4,
                'allowed_roles' => null,
            ],
            [
                'code' => 'webosistant',
                'name' => 'WebOsistant',
                'description' => 'CMS generator dan manajemen situs lab/unit kampus berbasis visual modern.',
                'icon' => 'globe',
                'route_name' => null,
                'status' => 'coming_soon',
                'order' => 5,
                'allowed_roles' => null,
            ],
            [
                'code' => 'inventory',
                'name' => 'SIMASS Inventaris',
                'description' => 'Pencatatan aset perangkat keras, peminjaman barang, dan log audit lab.',
                'icon' => 'package',
                'route_name' => null,
                'status' => 'coming_soon',
                'order' => 6,
                'allowed_roles' => null,
            ],
        ];

        foreach ($modules as $mod) {
            PortalModule::updateOrCreate(
                ['code' => $mod['code']],
                $mod
            );
        }
    }
}
