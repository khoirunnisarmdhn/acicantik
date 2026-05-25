<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalUmum extends Model
{
    protected $table = 'jurnal_umum';
    public $timestamps = false;

    protected $fillable = [
        'tanggal', 'id_coa', 'deskripsi', 'sumber_transaksi',
        'id_transaksi', 'nominal', 'posisi_dr_cr'
    ];

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'id_coa', 'id_coa');
    }
}
