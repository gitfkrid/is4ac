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
        'nama_device',
        'jenis_alat',
        'topic_mqtt',
        'status',
    ];

    public function detail_alat()
    {
        return $this->hasMany('App\Models\detail_alat', 'id_alat');
    }

    public function jenis_alat() {
        return $this->belongsTo('App\Models\jenis_alat', 'id_jenis_alat');
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }
}
