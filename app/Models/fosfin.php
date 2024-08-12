<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fosfin extends Model
{
    use HasFactory;

    protected $table = 'fosfin';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_alat',
        'fosfin',
    ];

    public function level() {
        return $this->belongsTo('App\Models\alat', 'id_alat');
    }
}
