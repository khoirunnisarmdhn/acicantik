<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    protected $table = 'kas';
    protected $primaryKey = 'id_kas';

    protected $fillable = [
        'no_form', 'tanggal', 'arus', 'id_kategori', 'id_proyek',
        'id_vendor', 'id_metode_bayar', 'id_termin_proyek',
        'nominal', 'keterangan', 'upload_bukti'
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriKas::class, 'id_kategori', 'id_kategori');
    }

    public function proyek()
    {
        return $this->belongsTo(Proyek::class, 'id_proyek', 'id_proyek');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor', 'id_vendor');
    }

    public function metodeBayar()
    {
        return $this->belongsTo(MetodeBayar::class, 'id_metode_bayar', 'id_metode_bayar');
    }

    public function terminProyek()
    {
        return $this->belongsTo(TerminProyek::class, 'id_termin_proyek', 'id_termin_proyek');
    }

    public function rincian()
    {
        return $this->hasMany(Rincian::class, 'id_kas', 'id_kas');
    }

    public function jurnalUmum()
    {
        return $this->hasMany(JurnalUmum::class, 'id_transaksi', 'id_kas');
    }
}
