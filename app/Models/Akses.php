<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Akses extends Model
{
    protected $table = 'akses';
    protected $primaryKey = 'id_akses';
    public $timestamps = false;

    protected $fillable = ['nama_akses', 'fitur_slug'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_akses', 'id_akses', 'user_id');
    }
}
