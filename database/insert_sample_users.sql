-- ============================================================
-- Insert User Contoh untuk Testing Role
-- Password semua: 12341234
-- Jalankan di phpMyAdmin
-- ============================================================

-- 1. Insert Users
INSERT INTO users (name, nama_lengkap, email, id_level, password, created_at, updated_at) VALUES
('siti_adm',    'Siti Nurhaliza',       'siti@perusahaan.com',      2, '$2y$12$XJhS5RqZ5QZ5QZ5QZ5QZ5OeYqZqZqZqZqZqZqZqZqZqZqZqZqZqZ', NOW(), NOW()),
('andi_keu',    'Andi Firmansyah',      'andi@perusahaan.com',      2, '$2y$12$XJhS5RqZ5QZ5QZ5QZ5QZ5OeYqZqZqZqZqZqZqZqZqZqZqZqZqZqZ', NOW(), NOW()),
('pak_hadi',    'Hadi Sutrisno, S.T.',  'hadi@perusahaan.com',      2, '$2y$12$XJhS5RqZ5QZ5QZ5QZ5QZ5OeYqZqZqZqZqZqZqZqZqZqZqZqZqZqZ', NOW(), NOW());

-- NOTE: Password hash di atas TIDAK VALID, gunakan cara di bawah ini:
-- Hapus 3 baris INSERT di atas, lalu jalankan via php artisan tinker:

-- Jalankan command ini di CMD (folder project):
-- php artisan tinker
-- Lalu paste satu per satu:

-- DB::table('users')->insert(['name'=>'siti_adm','nama_lengkap'=>'Siti Nurhaliza','email'=>'siti@perusahaan.com','id_level'=>2,'password'=>bcrypt('12341234'),'created_at'=>now(),'updated_at'=>now()]);
-- DB::table('users')->insert(['name'=>'andi_keu','nama_lengkap'=>'Andi Firmansyah','email'=>'andi@perusahaan.com','id_level'=>2,'password'=>bcrypt('12341234'),'created_at'=>now(),'updated_at'=>now()]);
-- DB::table('users')->insert(['name'=>'pak_hadi','nama_lengkap'=>'Hadi Sutrisno, S.T.','email'=>'hadi@perusahaan.com','id_level'=>2,'password'=>bcrypt('12341234'),'created_at'=>now(),'updated_at'=>now()]);

-- 2. Setelah user dibuat, cek ID-nya lalu assign role:
-- (Sesuaikan user_id dengan ID yang muncul setelah insert)

-- Contoh jika ID user baru = 3, 4, 5:
-- INSERT INTO user_akses (user_id, id_akses, created_at, updated_at) VALUES
-- (3, 3, NOW(), NOW()),  -- Siti -> Administrasi
-- (4, 4, NOW(), NOW()),  -- Andi -> Keuangan
-- (5, 5, NOW(), NOW());  -- Pak Hadi -> Pimpinan
