<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_lokasi extends Model
{
    use HasFactory;
    protected $table = 'user_lokasi';

    protected $fillable = [
        'id',
        'nama_lokasi',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'id');
    }

    public function lokasi() {
        return $this->belongsTo('App\Models\lokasi', 'id_lokasi');
    }
}
