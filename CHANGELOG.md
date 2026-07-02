# Changelog

Semua perubahan penting pada proyek **Labantik Point App** akan didokumentasikan di berkas ini.

## [1.1.0] - 2026-07-02

### Added
- **Sistem Hak Akses Baru (Role-Based Access Control)**:
  - Penambahan halaman pemilihan peran (`select-role.blade.php`) bagi pengguna yang memiliki lebih dari satu peran (Multi-role).
  - Penambahan middleware `CheckRole` untuk memvalidasi dan mengamankan rute berdasarkan peran aktif pengguna (Super Admin, BK, Guru).
  - Pembagian Modul Dashboard terpisah untuk setiap peran agar informasi lebih terfokus dan relevan.
- **Tombol Catatan Update**:
  - Penambahan tombol "Catatan Update" di bagian Topbar Admin untuk memberikan informasi pembaruan secara visual kepada pengguna secara langsung.

### Changed
- **Optimasi Tampilan Modal Rekap Tindakan**:
  - Mengubah lebar modal `modal-tindakan` dari `md:w-[30rem]` (480px) menjadi `md:w-[48rem] max-w-[95vw]` (768px) agar tampilan isian lebih lega dan proporsional.
  - Menerapkan tata letak grid responsif 2-kolom pada layar medium/besar (`md:grid-cols-2`) dan otomatis menjadi 1-kolom pada layar ponsel.
  - Memperlebar dropdown tindakan dengan kelas `w-full` agar mengikuti lebar kontainer modal.
  - Melakukan penyelarasan tampilan modal pada modul Super Admin (`confirm-recaps/index.blade.php`) dan modul BK (`bk/dashboard/recaps.blade.php`).

### Fixed
- Menghapus redundansi tag form close dan elemen tombol duplikat pada file view `bk/dashboard/recaps.blade.php`.

---

## [1.0.0] - 2026-05-02

### Added
- Penambahan berkas PDF template untuk Surat Panggilan Orang Tua siswa, Surat Pengembalian Siswa, dan Surat Perjanjian Siswa.
