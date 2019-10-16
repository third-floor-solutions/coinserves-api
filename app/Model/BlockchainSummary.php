<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Blockchain;

class BlockchainSummary extends Model
{
  protected $fillable = [
      'trees', 'tress_to_plant', 'trees_planted', 'trees_funded'
  ];
}
