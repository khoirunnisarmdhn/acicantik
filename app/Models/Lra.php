<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lra extends Model
{
    protected $table = 'lra';
    protected $primaryKey = 'id_lra';

    protected $fillable = ['keterangan', 'persentase', 'id_kategori'];

    public function kategoriKas()
    {
        return $this->belongsTo(KategoriKas::class, 'id_kategori', 'id_kategori');
    }
}
