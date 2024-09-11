<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class relay extends Model
{
    use HasFactory;

    protected $table = 'relay';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_alat',
        'state',
    ];

    public function level() {
        return $this->belongsTo('App\Models\alat', 'id_alat');
    }
}
