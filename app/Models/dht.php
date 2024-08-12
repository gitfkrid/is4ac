<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dht extends Model
{
    use HasFactory;

    protected $table = 'dht';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_alat',
        'suhu',
        'kelembapan',
    ];

    public function level() {
        return $this->belongsTo('App\Models\alat', 'id_alat');
    }
}
