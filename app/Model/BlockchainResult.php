<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BlockchainResult extends Model
{
    //
    protected $fillable = [
        'total_tx', 'tx', 'trees'
    ];
}
