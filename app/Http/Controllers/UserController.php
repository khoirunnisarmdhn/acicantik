<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index()
    {
        // Cukup ambil data user dasar, jangan di-join ke tabel akses di sini
        $users = DB::table('users')
            ->select('id', 'name', 'nama_lengkap', 'email')
            ->orderBy('id', 'desc')
            ->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $akses = DB::table('akses')->get();
        return view('users.create', compact('akses'));
    }

    /**
     * Menyimpan data user baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users,name',
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:users,email',
            'id_akses' => 'required|array', // Validasi harus pilih minimal 1
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // 1. Simpan ke tabel users
            $userId = DB::table('users')->insertGetId([
                'name' => $request->name,
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Simpan ke tabel pivot user_akses
            $dataAkses = [];
            foreach ($request->id_akses as $id_akses) {
                $dataAkses[] = [
                    'user_id' => $userId,
                    'id_akses' => $id_akses,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('user_akses')->insert($dataAkses);

            DB::commit();
            return redirect()->route('users.index')->with('success', 'User dan hak akses berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if (!$user)
            return redirect()->route('users.index')->with('error', 'User tidak ditemukan');

        $akses = DB::table('akses')->get();
        return view('users.edit', compact('user', 'akses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'name' => 'required|string|max:255|unique:users,name,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|confirmed|min:8',
            'id_akses' => 'required|array|min:1',
        ]);

        try {
            DB::beginTransaction();

            // 1. Update data dasar user
            $updateData = [
                'nama_lengkap' => $request->nama_lengkap,
                'name' => $request->name,
                'email' => $request->email,
                'updated_at' => now(),
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            DB::table('users')->where('id', $id)->update($updateData);

            // 2. Sync Hak Akses (Hapus lama, Insert baru)
            DB::table('user_akses')->where('user_id', $id)->delete();

            $dataAkses = [];
            foreach ($request->id_akses as $id_akses) {
                $dataAkses[] = [
                    'user_id' => $id,
                    'id_akses' => $id_akses,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('user_akses')->insert($dataAkses);

            DB::commit();
            return redirect()->route('users.index')->with('success', 'Data user berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    // destroy
    public function destroy($id)
    {
        try {
            // Karena pakai ON DELETE CASCADE di database, 
            // data di user_akses otomatis terhapus saat user dihapus.
            DB::table('users')->where('id', $id)->delete();
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}