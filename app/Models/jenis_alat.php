<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jenis_alat extends Model
{
    use HasFactory;

    protected $table = 'jenis_alat';

    protected $primaryKey = 'id_jenis_alat';

    protected $fillable = ['jenis_alat'];

    public $timestamps = false;

    public function alat()
    {
        return $this->hasMany('App\Models\alat', 'id_jenis_alat');
    }
}
