# Daftar File yang Ditambahkan dan Diubah

## Fitur Import APD, Obat, dan Alat Medis

### File Baru Dibuat (12 files)

#### Import Classes (3 files)
1. `app/Imports/ApdItemsImport.php` - Import data APD dari Excel
2. `app/Imports/KlinikObatsImport.php` - Import data obat dari Excel
3. `app/Imports/KlinikAlatsImport.php` - Import data alat medis dari Excel

#### Template Export Classes (3 files)
4. `app/Exports/TemplateApdItemsExport.php` - Template Excel untuk import APD
5. `app/Exports/TemplateKlinikObatsExport.php` - Template Excel untuk import obat
6. `app/Exports/TemplateKlinikAlatsExport.php` - Template Excel untuk import alat medis

#### Import Page Classes (3 files)
7. `app/Filament/AdminK3/Resources/ApdItemResource/Pages/ImportApdItems.php` - Halaman import APD (AdminK3)
8. `app/Filament/Klinik/Resources/KlinikObatResource/Pages/ImportKlinikObats.php` - Halaman import obat (Klinik)
9. `app/Filament/Klinik/Resources/KlinikAlatResource/Pages/ImportKlinikAlats.php` - Halaman import alat medis (Klinik)

#### View Files (3 files)
10. `resources/views/filament/admin-k3/resources/apd-item-resource/pages/import-apd-items.blade.php`
11. `resources/views/filament/klinik/resources/klinik-obat-resource/pages/import-klinik-obats.blade.php`
12. `resources/views/filament/klinik/resources/klinik-alat-resource/pages/import-klinik-alats.blade.php`

### File yang Diubah (6 files)

#### Resource Classes (3 files)
13. `app/Filament/AdminK3/Resources/ApdItemResource.php` - Menambahkan route 'import' ke getPages()
14. `app/Filament/Klinik/Resources/KlinikObatResource.php` - Menambahkan route 'import' ke getPages()
15. `app/Filament/Klinik/Resources/KlinikAlatResource.php` - Menambahkan route 'import' ke getPages()

#### List Page Classes (3 files)
16. `app/Filament/AdminK3/Resources/ApdItemResource/resourcePages/ListApdItems.php` - Menambahkan tombol Import di header
17. `app/Filament/Klinik/Resources/KlinikObatResource/resourcePages/ListKlinikObats.php` - Menambahkan tombol Import di header
18. `app/Filament/Klinik/Resources/KlinikAlatResource/resourcePages/ListKlinikAlats.php` - Menambahkan tombol Import di header

### Direktori Baru Dibuat (3 direktori)
- `resources/views/filament/admin-k3/resources/apd-item-resource/pages/`
- `resources/views/filament/klinik/resources/klinik-obat-resource/pages/`
- `resources/views/filament/klinik/resources/klinik-alat-resource/pages/`

---

## Fitur yang Tersedia

- **Import APD**: Upload Excel untuk批量 import/master data APD dengan validasi, auto-update berdasarkan kode_barang
- **Import Obat**: Upload Excel untuk批量 import data obat dengan validasi, auto-update berdasarkan kode_obat
- **Import Alat Medis**: Upload Excel untuk批量 import data alat medis, auto-update berdasarkan nama_barang
- **Download Template**: Tombol untuk download template Excel format yang sudah disesuaikan
- **Validasi**: Kolom wajib, format tanggal, nilai kondisi, dll.
- **Error Handling**: Baris error di-skip, import tetap lanjut dengan logging
