<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ViolationsSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua kategori dengan name sebagai key dan id(UUID) sebagai value
        $categories = DB::table('p_categories')->pluck('id', 'name');

        $violations = [
            // Ringan
            ['cat' => 'Ringan', 'name' => 'Memanjangkan/ mewarnai kuku', 'point' => 3],
            ['cat' => 'Ringan', 'name' => 'Tidak memakai  seragam dan atribut lengkap', 'point' => 3],
            ['cat' => 'Ringan', 'name' => 'Menggunakan aksesoris selain jam tangan', 'point' => 3],
            ['cat' => 'Ringan', 'name' => 'Tidak menjaga kerapihan pakaian', 'point' => 3],
            ['cat' => 'Ringan', 'name' => 'Tidak memakai ciput/ inner  hijab', 'point' => 3],
            ['cat' => 'Ringan', 'name' => 'Tidak menghadiri kegiatan PHBI dan PHBN yang diselenggarakan sekolah', 'point' => 5],
            ['cat' => 'Ringan', 'name' => 'Memakai dan membawa make up yang berlebihan kecuali sunscreen, lipbalm, parfum', 'point' => 5],
            ['cat' => 'Ringan', 'name' => 'Terlambat tiba di sekolah ', 'point' => 5],
            ['cat' => 'Ringan', 'name' => 'Meninggalkan pelajaran tanpa izin', 'point' => 5],

            // Sedang
            ['cat' => 'Sedang', 'name' => 'Tidak mengikuti upacara bendera', 'point' => 10],
            ['cat' => 'Sedang', 'name' => 'Tidak mengikuti kultum dan kegiatan rutin lainnya', 'point' => 10],
            ['cat' => 'Sedang', 'name' => 'Membawa dan bermain kartu di sekolah', 'point' => 10],
            ['cat' => 'Sedang', 'name' => 'Tidak mengikuti minimal satu kegiatan ekstrakuler', 'point' => 15],
            ['cat' => 'Sedang', 'name' => 'Tidak bisa menjaga sopan santun (berbicara kasar, mengejek dan menghina) di lingkungan sekolah', 'point' => 15],
            ['cat' => 'Sedang', 'name' => 'Rambut tidak sesuai ketentuan ukuran 321/diwarnai', 'point' => 15],
            ['cat' => 'Sedang', 'name' => 'Membawa teman yang bukan peserta didik SMKN 1 Talaga tanpa izin pihak keamanan', 'point' => 15],
            ['cat' => 'Sedang', 'name' => 'Tidak masuk ke sekolah tanpa keterangan (Alpa)', 'point' => 15],
            ['cat' => 'Sedang', 'name' => 'Mengaktifkan HP pada saat KBM (kecuali izin guru mapel)', 'point' => 15],
            ['cat' => 'Sedang', 'name' => 'Menggunakan kendaraan dengan knalpot bising', 'point' => 20],
            ['cat' => 'Sedang', 'name' => 'Melakukan tindakan vandalisme dan merusak fasilitas sekolah', 'point' => 20],
            ['cat' => 'Sedang', 'name' => 'Masuk dan keluar sekolah tidak melalui pintu gerbang utama', 'point' => 20],
            ['cat' => 'Sedang', 'name' => 'Main game online dilingkungan sekolah', 'point' => 25],
            ['cat' => 'Sedang', 'name' => 'Terlibat dalam aktifitas politik praktis', 'point' => 25],

            // Berat
            ['cat' => 'Berat', 'name' => 'Mengakses dan menyimpan hal-hal berbentuk pornoaksi dan pornografi', 'point' => 30],
            ['cat' => 'Berat', 'name' => 'Merokok dan memperjual belikan rokok', 'point' => 30],
            ['cat' => 'Berat', 'name' => 'Melakukan provokasi (bolos, berkelahi, tawuran antar pelajar)', 'point' => 50],
            ['cat' => 'Berat', 'name' => 'Merusak barang milik warga sekolah', 'point' => 50],
            ['cat' => 'Berat', 'name' => 'Mempropagandakan komunitas yang tidak sehat', 'point' => 50],
            ['cat' => 'Berat', 'name' => 'Memakai tato/ tindik', 'point' => 50],
            ['cat' => 'Berat', 'name' => 'Mencederai atau melakukan tindakan kekerasan lain terhadap teman atau orang lain', 'point' => 50],
            ['cat' => 'Berat', 'name' => 'Melakukan pelecehan seksual berupa verbal maupun nonverbal', 'point' => 50],
            ['cat' => 'Berat', 'name' => 'Melakukan bullying baik secara fisik, verbal, maupun cyber bullying', 'point' => 50],
            ['cat' => 'Berat', 'name' => 'Memposting foto/ video asusila', 'point' => 75],
            ['cat' => 'Berat', 'name' => 'Membawa senjata tajam', 'point' => 75],
            ['cat' => 'Berat', 'name' => 'Terlibat dalam geng motor (ORMAS) atau kelompok yang tidak sehat', 'point' => 80],
            ['cat' => 'Berat', 'name' => 'Melakukan perbuatan asusila baik secara langsung maupun tidak langsung', 'point' => 100],
            ['cat' => 'Berat', 'name' => 'Menantang, mengancam dan menyakiti karyawan, guru dan kepala sekolah', 'point' => 100],
            ['cat' => 'Berat', 'name' => 'Membawa, mengedarkan, mengkonsumsi narkoba dan minuman keras', 'point' => 100],
            ['cat' => 'Berat', 'name' => 'Membawa senjata api', 'point' => 100],
            ['cat' => 'Berat', 'name' => 'Terlibat dalam perjudian dan pencurian', 'point' => 100],
            ['cat' => 'Berat', 'name' => 'Terlibat tawuran dengan sekolah lain', 'point' => 100],
        ];

        $insertData = [];
        foreach ($violations as $v) {
            $insertData[] = [
                'id' => (string) Str::uuid(),
                'p_category_id' => $categories[$v['cat']], // ambil UUID kategori
                'name' => $v['name'],
                'point' => $v['point']
            ];
        }

        DB::table('p_violations')->insert($insertData);
    }
}
