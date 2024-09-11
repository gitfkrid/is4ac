<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi';
    protected $primaryKey = 'id_lokasi';
    protected $fillable = ['nama_lokasi'];

    public $timestamps = false;

    public function user_lokasi()
    {
        return $this->hasMany('App\Models\user_lokasi', 'id_lokasi');
    }
}
