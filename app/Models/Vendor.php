<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $primaryKey = 'id_vendor';

    protected $fillable = ['nama', 'alamat', 'penanggung_jawab', 'no_telp', 'email'];

    public function kas()
    {
        return $this->hasMany(Kas::class, 'id_vendor', 'id_vendor');
    }
}
