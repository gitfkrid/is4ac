<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class log_relay extends Model
{
    use HasFactory;
    protected $table = 'log_relay';

    protected $primaryKey = 'id_log';

    protected $fillable = [
        'waktu',
        'keterangan',
    ];
}
