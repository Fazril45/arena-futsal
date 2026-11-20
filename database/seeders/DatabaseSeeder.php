<?php

namespace Database\Seeders;

use App\Enums\StatusBooking;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Arena',
            'email' => 'admin@arenafutsal.test',
            'password' => Hash::make('password'),
            'no_hp' => '081234567890',
            'role' => 'admin',
        ]);

        $pelanggan1 = User::create([
            'name' => 'Fazril Saputra',
            'email' => 'fazril@arenafutsal.test',
            'password' => Hash::make('password'),
            'no_hp' => '081298765432',
            'role' => 'pelanggan',
        ]);

        $pelanggan2 = User::create([
            'name' => 'Damar',
            'email' => 'damar@arenafutsal.test',
            'password' => Hash::make('password'),
            'no_hp' => '085711223344',
            'role' => 'pelanggan',
        ]);

        $pelanggan3 = User::create([
            'name' => 'Agung',
            'email' => 'agung@arenafutsal.test',
            'password' => Hash::make('password'),
            'no_hp' => '085799887766',
            'role' => 'pelanggan',
        ]);

        $lap1 = Lapangan::create([
            'nama' => 'Lapangan A (Matras Vinyl Pro)',
            'jenis_lantai' => 'Vinyl Interlock',
            'deskripsi' => 'Lapangan standar nasional dengan pencahayaan LED terang dan sirkulasi udara maksimal.',
            'harga_reguler' => 100000,
            'harga_prime' => 150000,
            'aktif' => true,
        ]);

        $lap2 = Lapangan::create([
            'nama' => 'Lapangan B (Rumput Sintetis)',
            'jenis_lantai' => 'Rumput Sintetis FIFA',
            'deskripsi' => 'Rumput sintetis empuk dengan butiran karet premium, nyaman untuk permainan cepat.',
            'harga_reguler' => 90000,
            'harga_prime' => 135000,
            'aktif' => true,
        ]);

        $lap3 = Lapangan::create([
            'nama' => 'Lapangan C (Semen Plester Halus)',
            'jenis_lantai' => 'Semen Coating',
            'deskripsi' => 'Lapangan ekonomis cocok untuk latihan rutin dan sparring santai.',
            'harga_reguler' => 70000,
            'harga_prime' => 100000,
            'aktif' => true,
        ]);

        $besok = Carbon::tomorrow()->format('Y-m-d');
        $hariIni = Carbon::today()->format('Y-m-d');

        Booking::create([
            'kode_booking' => 'BK-' . date('Ymd') . '-0001',
            'user_id' => $pelanggan1->id,
            'nama_pemesan' => $pelanggan1->name,
            'no_hp_pemesan' => $pelanggan1->no_hp,
            'lapangan_id' => $lap1->id,
            'tanggal' => $besok,
            'jam_mulai' => '19:00',
            'jam_selesai' => '21:00',
            'durasi' => 2,
            'total_harga' => 300000,
            'status' => StatusBooking::Dikonfirmasi,
        ]);

        Booking::create([
            'kode_booking' => 'BK-' . date('Ymd') . '-0002',
            'user_id' => $pelanggan2->id,
            'nama_pemesan' => $pelanggan2->name,
            'no_hp_pemesan' => $pelanggan2->no_hp,
            'lapangan_id' => $lap1->id,
            'tanggal' => $besok,
            'jam_mulai' => '16:00',
            'jam_selesai' => '18:00',
            'durasi' => 2,
            'total_harga' => 250000,
            'status' => StatusBooking::MenungguPembayaran,
            'batas_bayar' => Carbon::now()->addHour(),
        ]);

        Booking::create([
            'kode_booking' => 'BK-' . date('Ymd') . '-0003',
            'user_id' => $pelanggan3->id,
            'nama_pemesan' => $pelanggan3->name,
            'no_hp_pemesan' => $pelanggan3->no_hp,
            'lapangan_id' => $lap2->id,
            'tanggal' => $hariIni,
            'jam_mulai' => '10:00',
            'jam_selesai' => '11:00',
            'durasi' => 1,
            'total_harga' => 90000,
            'status' => StatusBooking::Kedaluwarsa,
            'batas_bayar' => Carbon::now()->subHours(2),
        ]);
    }
}