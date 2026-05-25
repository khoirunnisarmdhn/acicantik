<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAkses extends Model
{
    protected $table = 'user_akses';

    protected $fillable = ['user_id', 'id_akses'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function akses()
    {
        return $this->belongsTo(Akses::class, 'id_akses', 'id_akses');
    }
}
