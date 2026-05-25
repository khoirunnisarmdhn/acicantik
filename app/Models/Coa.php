<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    protected $table = 'coa';
    protected $primaryKey = 'id_coa';

    protected $fillable = ['kode_akun', 'nama_akun', 'tipe', 'saldo_normal', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Coa::class, 'parent_id', 'id_coa');
    }

    public function children()
    {
        return $this->hasMany(Coa::class, 'parent_id', 'id_coa');
    }

    public function jurnalUmum()
    {
        return $this->hasMany(JurnalUmum::class, 'id_coa', 'id_coa');
    }
}
