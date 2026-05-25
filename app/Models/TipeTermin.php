<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipeTermin extends Model
{
    protected $table = 'tipe_termin';
    protected $primaryKey = 'id_tipe_termin';

    protected $fillable = ['nama_termin', 'deskripsi'];

    public function terminProyek()
    {
        return $this->hasMany(TerminProyek::class, 'id_tipe_termin', 'id_tipe_termin');
    }
}
