<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blockchain extends Model
{
    use SoftDeletes;

    protected $table = 'blockchains';
    
    protected $fillable = [
        'cnsrv_n_tx'
    ];
}
