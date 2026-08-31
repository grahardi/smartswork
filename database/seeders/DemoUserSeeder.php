<?php

namespace Database\Seeders;

use App\Models\DailyAction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::updateOrCreate(
            ['email' => 'demo@smarts.id'],
            [
                'name' => 'Demo SMARTS Work',
                'password' => Hash::make(str()->random(32)),
                'is_demo' => true,
                'email_verified_at' => now(),
            ]
        );

        $demo->profile()->updateOrCreate(
            ['user_id' => $demo->id],
            [
                'nama_lengkap' => 'Pengguna Demo',
                'no_hp' => '081234567890',
                'alamat' => 'Turen, Kabupaten Malang',
            ]
        );

        // Reset relasi lama biar seeder aman dijalankan berulang kali.
        $demo->workplaces()->detach();
        $demo->workplaces()->each(fn ($w) => $w->projects()->delete());

        $pribadi = $demo->provisionDefaultWorkplace();

        $kantor = $demo->workplaces()->create([
            'nama' => 'Bellanet (Contoh)',
            'alamat' => 'Turen, Kabupaten Malang',
            'type' => 'formal',
            'is_default' => false,
        ]);
        $demo->workplaces()->attach($kantor->id, [
            'jabatan' => 'Teknisi Jaringan',
            'tanggal_gabung' => now()->subMonths(6),
        ]);

        $project = $kantor->projects()->create([
            'nama' => 'Perluasan Jaringan Area Turen',
            'planning' => 'Pasang tiang baru dan tarik kabel fiber ke 3 RW.',
            'target' => 'Selesai dan online sebelum akhir bulan.',
            'status' => 'berjalan',
        ]);

        $projectPribadi = $pribadi->projects()->first();

        DailyAction::insert([
            [
                'user_id' => $demo->id,
                'project_id' => $project->id,
                'tanggal' => now()->subDays(2)->toDateString(),
                'waktu' => '09:00:00',
                'keterangan' => 'Survey lokasi tiang baru di RW 03.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $demo->id,
                'project_id' => $projectPribadi->id,
                'tanggal' => now()->subDay()->toDateString(),
                'waktu' => '07:00:00',
                'keterangan' => 'Antar anak ke sekolah.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $demo->id,
                'project_id' => $project->id,
                'tanggal' => now()->toDateString(),
                'waktu' => '10:30:00',
                'keterangan' => 'Tarik kabel fiber tahap 1, progres 40%.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
