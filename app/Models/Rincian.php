<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rincian extends Model
{
    protected $table = 'rincian';

    protected $fillable = ['id_kas', 'nama', 'nominal'];

    public function kas()
    {
        return $this->belongsTo(Kas::class, 'id_kas', 'id_kas');
    }
}
