<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyek extends Model
{
    protected $table = 'proyek';
    protected $primaryKey = 'id_proyek';

    protected $fillable = [
        'nama', 'id_pemberi', 'nilai_kontrak', 'target_laba',
        'jumlah_termin', 'tanggal_mulai', 'tanggal_selesai', 'status', 'deskripsi'
    ];

    public function pemberiProyek()
    {
        return $this->belongsTo(PemberiProyek::class, 'id_pemberi', 'id_pemberi');
    }

    public function terminProyek()
    {
        return $this->hasMany(TerminProyek::class, 'id_proyek', 'id_proyek');
    }

    public function kas()
    {
        return $this->hasMany(Kas::class, 'id_proyek', 'id_proyek');
    }
}
