<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Tabel Akses (Master List Fitur)
        Schema::create('akses', function (Blueprint $table) {
            $table->id('id_akses');
            $table->string('nama_akses', 50);
            $table->text('fitur_slug')->nullable();
        });

        DB::table('akses')->insert([
            [
                'nama_akses' => 'Admin',
                'fitur_slug' => 'all',
            ],
            [
                'nama_akses' => 'Administrasi',
                'fitur_slug' => 'dashboard,klien,vendor,kas_masuk,kas_keluar,termin,coa,lra',
            ],
            [
                'nama_akses' => 'Keuangan',
                'fitur_slug' => 'dashboard,jurnal,laporan_realisasi,buku_besar',
            ],
            [
                'nama_akses' => 'Pimpinan',
                'fitur_slug' => 'dashboard,jurnal,laporan_realisasi,buku_besar',
            ],
        ]);

        // 2. Tabel Users (DENGAN id_level)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique(); // Username
            $table->string('nama_lengkap', 100)->nullable();
            $table->string('email')->unique();
            $table->integer('id_level')->default(2); // 1: Admin, 2: User/Staff
            $table->string('password');
            $table->datetime('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert([
            ['name' => 'admin',        'nama_lengkap' => 'admin',        'email' => 'admin@admin.com',               'id_level' => 1, 'password' => bcrypt('12341234'), 'created_at' => now()],
            ['name' => 'keuangan',     'nama_lengkap' => 'keuangan',     'email' => 'finance@finance.com',           'id_level' => 2, 'password' => bcrypt('12341234'), 'created_at' => now()],
            ['name' => 'administrasi', 'nama_lengkap' => 'administrasi', 'email' => 'administrasi@administrasi.com', 'id_level' => 2, 'password' => bcrypt('12341234'), 'created_at' => now()],
            ['name' => 'pemimpin',     'nama_lengkap' => 'pemimpin',     'email' => 'pemimpin@pemimpin.com',         'id_level' => 2, 'password' => bcrypt('12341234'), 'created_at' => now()],
        ]);

        // 3. Tabel Pivot (Biar 1 User bisa punya banyak akses)
        Schema::create('user_akses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_akses')->constrained('akses', 'id_akses')->onDelete('cascade');
            $table->timestamps();
        });

        DB::table('user_akses')->insert([
            ['user_id' => 1, 'id_akses' => 1, 'created_at' => now(), 'updated_at' => now()], // admin -> Admin
            ['user_id' => 2, 'id_akses' => 3, 'created_at' => now(), 'updated_at' => now()], // keuangan -> Keuangan
            ['user_id' => 3, 'id_akses' => 2, 'created_at' => now(), 'updated_at' => now()], // administrasi -> Administrasi
            ['user_id' => 4, 'id_akses' => 4, 'created_at' => now(), 'updated_at' => now()], // pemimpin -> Pimpinan
        ]);

        // 4. Tabel Pendukung Laravel
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('user_akses');
        Schema::dropIfExists('users');
        Schema::dropIfExists('akses');
    }
};