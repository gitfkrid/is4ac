<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class nilaibatas extends Model
{
    use HasFactory;
    protected $table = 'nilaibatas';

    protected $primaryKey = 'id_nilaibatas';
    protected $fillable = [
        'nb_suhu_atas', 'nb_suhu_bawah', 'nb_rh_atas', 'nb_rh_bawah', 'nb_ph3_atas', 'nb_ph3_bawah'
    ];
    public $timestamps = false;

}
