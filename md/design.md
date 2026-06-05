# Design System - Website Jasa Dekorasi Pernikahan

## Tema Visual
Elegan, Romantis, Bersih (Clean), dan Modern. Menggunakan pendekatan minimalis dengan tipografi yang anggun agar foto dekorasi menjadi daya tarik utama.

## Palet Warna (Tailwind Classes)
- **Primary (Warna Utama):** Rose Gold / Blush Pink
  - Hex: #F4E0DB (Soft Base) | #E11D48 (Rose-600 untuk Aksen)
- **Secondary (Warna Pendukung):** Sage Green / Olive (Memberikan kesan dekorasi daun/floral)
  - Hex: #15803D (Green-700) | #F0FDF4 (Green-50)
- **Neutral Dark (Teks Utama):** Charcoal / Off-Black (Kesan premium)
  - Hex: #1F2937 (Gray-800)
- **Neutral Light (Background):** Warm White / Cream
  - Hex: #FAFAFA (Stone-50) | #FFFDFB (Custom Ivory)

## Tipografi (Google Fonts)
- **Heading (Judul Paket/Hero):** Playfair Display (Serif - Elegan & Klasik)
- **Body Text (Konten/Deskripsi):** Inter / Plus Jakarta Sans (Sans-serif - Modern & Readable)

## Komponen Layout
1. **Landing Page:**
   - **Hero Section:** Full-screen image banner dengan overlay teks menggunakan font Serif besar dan tombol CTA (Call to Action) "Konsultasi Sekarang".
   - **Kategori & Paket:** Grid layout 3 kolom menggunakan Card modern dengan efek shadow halus (`shadow-sm` ke `hover:shadow-lg`).
   - **Portofolio:** Masonry grid atau Flex-wrap galeri foto interaktif.
   - **Testimoni:** Slider atau grid berisi kartu ulasan klien dengan rating bintang emas.

2. **Admin Panel:**
   - **Sidebar:** Clean dark/white sidebar (`bg-slate-900` atau `bg-white border-r`).
   - **Table:** Clean borderless table dengan status badge berwarna (e.g., hijau untuk pesan yang sudah dibaca).
   - **Form:** Minimalis dengan rounded corners (`rounded-lg`) dan fokus border rose (`focus:border-rose-500`).