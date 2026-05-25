<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TerminProyek extends Model
{
    protected $table = 'termin_proyek';
    protected $primaryKey = 'id_termin_proyek';

    protected $fillable = [
        'id_proyek', 'id_tipe_termin', 'persentase', 'progress_keterangan',
        'nominal', 'keterangan', 'due_date', 'status_pembayaran'
    ];

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    public function tipeTermin()
    {
        return $this->belongsTo(TipeTermin::class, 'id_tipe_termin', 'id_tipe_termin');
    }

    public function kas()
    {
        return $this->hasMany(Kas::class, 'id_termin_proyek', 'id_termin_proyek');
    }
}
