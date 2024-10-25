<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class log extends Model
{
    use HasFactory;
    protected $table = 'log';

    protected $primaryKey = 'id_log';
    protected $fillable = [
        'id_alat',
        'suhu',
        'kelembaban',
    ];

    public function level() {
        return $this->belongsTo('App\Models\alat', 'id_alat');
    }
}
