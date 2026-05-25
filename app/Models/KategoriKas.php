<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKas extends Model
{
    protected $table = 'kategori_kas';
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'nama_kategori', 'arus', 'jenis', 'deskripsi', 'id_coa_debit', 'id_coa_kredit'
    ];

    public function coaDebit()
    {
        return $this->belongsTo(Coa::class, 'id_coa_debit', 'id_coa');
    }

    public function coaKredit()
    {
        return $this->belongsTo(Coa::class, 'id_coa_kredit', 'id_coa');
    }

    public function kas()
    {
        return $this->hasMany(Kas::class, 'id_kategori', 'id_kategori');
    }

    public function lra()
    {
        return $this->hasMany(Lra::class, 'id_kategori', 'id_kategori');
    }
}
