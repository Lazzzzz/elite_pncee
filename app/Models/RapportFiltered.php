<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RapportFiltered extends Model
{
    protected $table = 'rapports_filtered';
    public $timestamps = false;

    protected $connection = 'elite';

    protected $fillable = [
        'rapport_id',
    ];
}
