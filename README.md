Alur Bisnis

1. Pendaftaran Pasien  
   ▶ **Input**:
    - **Pasien mengisi sendiri** data pendaftaran melalui sistem: - Nama - NIK - Umur - Poli Tujuan  
      ▶ **Proses**:
    - Jika pasien **baru** → Sistem menyimpan data pasien ke database.
    - Jika pasien **lama** → Sistem menampilkan riwayat kunjungan sebelumnya.
    - Sistem mencatat **poli yang dipilih pasien**.  
      ▶ **Output**:
    - Data pasien tersimpan dalam sistem.
    - Sistem menampilkan poli tujuan pasien.
    - Data pendaftaran pasien diteruskan ke dokter.

▼

2. Sistem Meneruskan Data ke Dokter  
   ▶ **Input**:
    - Data pasien yang telah mendaftar.  
      ▶ **Proses**:
    - Sistem menampilkan daftar pasien berdasarkan poli tujuan.
    - Dokter dapat melihat **riwayat kunjungan dan rekam medis** pasien jika tersedia.  
      ▶ **Output**:
    - Dokter mengetahui pasien yang sedang menunggu pemeriksaan.
    - Dokter bisa memanggil pasien untuk diperiksa.

▼

3. Pemeriksaan oleh Dokter  
   ▶ **Input**:
    - Data pasien dari sistem.  
      ▶ **Proses**:
    - Dokter melakukan pemeriksaan dan mencatat hasil diagnosa.
    - Jika pasien memerlukan obat → Dokter membuat resep di sistem.
    - Sistem menyimpan diagnosa dan resep ke dalam database.  
      ▶ **Output**:
    - Rekam medis pasien diperbarui.
    - Resep otomatis diteruskan ke bagian farmasi.

▼

4. Sistem Meneruskan Data ke Farmasi  
   ▶ **Input**:
    - Resep dari dokter.  
      ▶ **Proses**:
    - Sistem mengirimkan resep ke farmasi.
    - Farmasi melihat daftar resep yang harus diproses.
    - **Farmasi memeriksa apakah obat termasuk racik atau non-racik**.
        - Jika **Non-Racik** → Obat langsung diambil dari stok.
        - Jika **Racik** → Apoteker mencampurkan obat sesuai resep.
    - Sistem menghitung total harga obat dan mencetak tagihan.  
      ▶ **Output**:
    - Resep siap diproses farmasi.
    - Sistem menampilkan rincian harga obat untuk pasien.
    - Tagihan obat dibuat dan diteruskan ke kasir.

▼

5. Sistem Mengelola Pembayaran di Kasir  
   ▶ **Input**:
    - Tagihan dari farmasi.  
      ▶ **Proses**:
    - Pasien menuju kasir untuk membayar.
    - Kasir memasukkan pembayaran ke dalam sistem.
    - Jika **lunas** → Sistem memberikan notifikasi ke farmasi bahwa obat bisa diambil.
    - Jika **belum lunas** → Status pembayaran tetap **tertunda**.  
      ▶ **Output**:
    - Jika **lunas** → Pasien bisa mengambil obat.
    - Jika **belum lunas** → Obat tidak diberikan.

▼

6. Sistem Meneruskan Konfirmasi ke Farmasi  
   ▶ **Input**:
    - Status pembayaran dari kasir.  
      ▶ **Proses**:
    - Jika **lunas** → Sistem mengonfirmasi ke farmasi untuk memberikan obat.
    - Jika **belum lunas** → Sistem menandai transaksi sebagai **tertunda**, dan obat tidak diberikan.  
      ▶ **Output**:
    - Jika **lunas** → Farmasi memberikan obat kepada pasien.
    - Jika **belum lunas** → Pasien harus kembali ke kasir untuk pembayaran.

▼

7.  Pasien Meninggalkan Klinik  
     ▶ **Input**: - Data pasien, pembayaran, dan obat yang telah diberikan.  
     ▶ **Proses**: - Sistem menyimpan seluruh transaksi (pendaftaran, pemeriksaan, resep, pembayaran, obat). - Jika pasien perlu kontrol ulang → Sistem mencatat jadwal kunjungan berikutnya.  
     ▶ **Output**: - **Riwayat kunjungan pasien tersimpan di sistem.** - **Pasien meninggalkan klinik dengan obat yang diberikan.**
    <br>

# 📌 Alur Sistem Informasi

1️⃣ **Pasien Mendaftar** → Data masuk ke tabel **pendaftaran**. <br>
2️⃣ **Pasien Diperiksa Dokter** → Data masuk ke tabel **pemeriksaan**.<br>
3️⃣ **Dokter Menulis Resep** → Data masuk ke tabel **resep & resep_detail**.<br>
4️⃣ **Farmasi Menyiapkan Obat** → **Stok obat** berkurang sesuai resep.<br>
5️⃣ **Pasien Membayar di Kasir** → Data masuk ke tabel **transaksi**.<br>
6️⃣ **Jika Lunas, Farmasi Memberikan Obat** → Data masuk ke tabel **pengambilan_obat**.<br>

## 📌 1. Tabel `tb_pasien`

    Menyimpan data pasien yang mendaftar di klinik.

| Nama Kolom             | Tipe Data       | Keterangan               |
| ---------------------- | --------------- | ------------------------ |
| `id_pasien`            | INT (PK, AI)    | ID unik pasien           |
| `nama_pasien`          | VARCHAR(255)    | Nama lengkap pasien      |
| `nik_pasien`           | VARCHAR(16)     | Nomor Induk Kependudukan |
| `tanggallahir_pasien`  | INT             | Usia pasien              |
| `jenis_kelamin_pasien` | ENUM ('L', 'P') | Jenis kelamin pasien     |
| `alamat_pasien`        | TEXT            | Alamat pasien            |
| `no_hp_pasien`         | VARCHAR(15)     | Nomor HP pasien          |

## 📌 2. Tabel `tb_poli`

Menyimpan data poli yang tersedia di klinik.

| Nama Kolom  | Tipe Data    | Keterangan                          |
| ----------- | ------------ | ----------------------------------- |
| `id_poli`   | INT (PK, AI) | ID unik poli                        |
| `nama_poli` | VARCHAR(100) | Nama poli (misal: Umum, Gigi, dll.) |

## 📌 3. Tabel `tb_pendaftaran`

Menyimpan data pasien yang mendaftar ke klinik berdasarkan poli yang dipilih.

| Nama Kolom                   | Tipe Data                    | Keterangan            |
| ---------------------------- | ---------------------------- | --------------------- |
| `id_pendaftaran`             | INT (PK, AI)                 | ID unik pendaftaran   |
| `id_pasien`                  | INT (FK)                     | Referensi ke `pasien` |
| `id_poli`                    | INT (FK)                     | Referensi ke `poli`   |
| `status_pendaftaran`         | ENUM ('Menunggu', 'Selesai') | Status pendaftaran    |
| `tanggal_daftar_pendaftaran` | TIMESTAMP                    | Waktu pendaftaran     |

## 📌 4. Tabel `tb_dokter`

Menyimpan data dokter yang bertugas.

| Nama Kolom            | Tipe Data    | Keterangan          |
| --------------------- | ------------ | ------------------- |
| `id_dokter`           | INT (PK, AI) | ID unik dokter      |
| `nama_dokter`         | VARCHAR(255) | Nama dokter         |
| `id_poli`             | INT (FK)     | Referensi ke `poli` |
| `spesialisasi_dokter` | VARCHAR(100) | Spesialisasi dokter |
| `no_hp_dokter`        | VARCHAR(15)  | Nomor HP dokter     |

## 📌 5. Tabel `tb_pemeriksaan`

Menyimpan hasil pemeriksaan pasien oleh dokter.

| Nama Kolom                    | Tipe Data    | Keterangan                 |
| ----------------------------- | ------------ | -------------------------- |
| `id_pemeriksaan`              | INT (PK, AI) | ID unik pemeriksaan        |
| `id_pendaftaran`              | INT (FK)     | Referensi ke `pendaftaran` |
| `id_dokter`                   | INT (FK)     | Referensi ke `dokter`      |
| `diagnosa_pemeriksaan`        | TEXT         | Diagnosa dokter            |
| `catatan_pemeriksaan`         | TEXT         | Catatan tambahan           |
| `tanggal_periksa_pemeriksaan` | TIMESTAMP    | Waktu pemeriksaan          |

## 📌 6. Tabel `tb_obat`

Menyimpan daftar obat yang tersedia di farmasi.

| Nama Kolom    | Tipe Data                       | Keterangan           |
| ------------- | ------------------------------- | -------------------- |
| `id_obat`     | INT (PK, AI)                    | ID unik obat         |
| `nama_obat`   | VARCHAR(255)                    | Nama obat            |
| `jenis_obat`  | ENUM ('Racikan', 'Non-Racikan') | Jenis obat           |
| `satuan_obat` | VARCHAR(50)                     | Satuan (mg/ml)       |
| `stok_obat`   | INT                             | Jumlah stok tersedia |
| `harga_obat`  | DECIMAL(10,2)                   | Harga satuan obat    |

## 📌 7. Tabel `tb_resep`

Menyimpan resep obat yang diberikan oleh dokter tambahakan id_pendaftaran

| Nama Kolom       | Tipe Data                                | Keterangan                 |
| ---------------- | ---------------------------------------- | -------------------------- |
| `id_resep`       | INT (PK, AI)                             | ID unik resep              |
| `id_pendaftaran` | INT (FK)                                 | Referensi ke `pendaftaran` |
| `id_pemeriksaan` | INT (FK)                                 | Referensi ke `pemeriksaan` |
| `status_resep`   | ENUM ('Menunggu', 'Diproses', 'Selesai') | Status resep               |

## 📌 8. Tabel `tb_resep_detail`

Menyimpan detail obat yang diresepkan dalam satu resep.

| Nama Kolom                  | Tipe Data     | Keterangan                 |
| --------------------------- | ------------- | -------------------------- |
| `id_resep_detail`           | INT (PK, AI)  | ID unik                    |
| `id_resep`                  | INT (FK)      | Referensi ke `resep`       |
| `id_obat`                   | INT (FK)      | Referensi ke `obat`        |
| `total_harga_resep`         | DECIMAL(10,2) | Total harga resep          |
| `dosis_resep_detail`        | VARCHAR(50)   | Dosis obat                 |
| `jumlah_resep_detail`       | INT           | Jumlah obat yang diberikan |
| `aturan_pakai_resep_detail` | TEXT          | Aturan pemakaian obat      |

## 📌 9. Tabel `tb_transaksi`

Menyimpan pembayaran pasien terkait resep obat.

| Nama Kolom                | Tipe Data                     | Keterangan                |
| ------------------------- | ----------------------------- | ------------------------- |
| `id_transaksi`            | INT (PK, AI)                  | ID unik transaksi         |
| `id_resep`                | INT (FK)                      | Referensi ke `resep`      |
| `total_bayar_transaksi`   | DECIMAL(10,2)                 | Jumlah yang harus dibayar |
| `status_transaksi`        | ENUM ('Lunas', 'Belum Lunas') | Status pembayaran         |
| `tanggal_bayar_transaksi` | TIMESTAMP                     | Waktu pembayaran          |

## 📌 10. Tabel `tb_pengambilan_obat`

Menyimpan data pengambilan obat oleh pasien setelah pembayaran.

| Nama Kolom                       | Tipe Data                         | Keterangan             |
| -------------------------------- | --------------------------------- | ---------------------- |
| `id_pengambilan`                 | INT (PK, AI)                      | ID unik pengambilan    |
| `id_resep`                       | INT (FK)                          | Referensi ke `resep`   |
| `status_pengambilan_obat`        | ENUM ('Diambil', 'Belum Diambil') | Status pengambilan     |
| `tanggal_ambil_pengambilan_obat` | TIMESTAMP                         | Waktu pengambilan obat |

## 📌 Pembagian Tugas

| Nama      | Tugas                                                                                                                                                               |
| --------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| **Wita**  | 1️⃣ **Pasien Mendaftar** → Data masuk ke tabel **pendaftaran**. <br> 2️⃣ **Pasien Diperiksa Dokter** → Data masuk ke tabel **pemeriksaan**.<br>                       |
| **Citra** | 3️⃣ **Dokter Menulis Resep** → Data masuk ke tabel **resep & resep_detail**.<br> 4️⃣ **Farmasi Menyiapkan Obat** → **Stok obat** berkurang sesuai resep.<br>          |
| **Deva**  | 5️⃣ **Pasien Membayar di Kasir** → Data masuk ke tabel **transaksi**.<br> 6️⃣ **Jika Lunas, Farmasi Memberikan Obat** → Data masuk ke tabel **pengambilan_obat**.<br> |
