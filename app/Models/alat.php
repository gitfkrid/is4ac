<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class alat extends Model
{
    use HasFactory;

    protected $table = 'alat';

    protected $primaryKey = 'id_alat';

    protected $fillable = [
        'kode_board',
        'nama_device',
        'id_jenis_alat',
        'id_lokasi',
        'status',
    ];

    public function detail_alat()
    {
        return $this->hasMany('App\Models\detail_alat', 'id_alat');
    }

    public function jenis_alat() {
        return $this->belongsTo('App\Models\jenis_alat', 'id_jenis_alat');
    }

    public function lokasi() {
        return $this->belongsTo('App\Models\lokasi', 'id_lokasi');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
