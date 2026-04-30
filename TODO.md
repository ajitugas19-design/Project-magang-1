# TODO - Update KM Total Formula

## Task: Ubah formula km_total = km_keluar - km_masuk (km_datang)

### Progress:
- [x] Analisis file (Dashbord.php, edit.php, simpan.php)
- [x] Edit Dashbord.php - ubah formula JS
- [x] Edit edit.php - ubah formula JS
- [ ] Testing

### Detail Perubahan:

**Old Formula:** `km_total = km_datang - km_keluar`
**New Formula:** `km_total = km_keluar - km_datang`

### File yang Diedit:

1. Dashbord.php - line ~190 (function hitungKM)
2. edit.php - line ~200 (function hitungKM)
