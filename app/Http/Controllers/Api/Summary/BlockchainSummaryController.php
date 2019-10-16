<?php

namespace App\Http\Controllers\Api\Summary;

use App\Http\Controllers\Controller;
use App\Model\Blockchain;
use App\Model\BlockchainSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlockchainSummaryController extends Controller
{
    protected $blockchainRepository;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getSummaryTrees(){
        $blockchain = Blockchain::select(DB::raw('SUM(trees) AS total_tx'))
               ->first();
        $summary = BlockchainSummary::first();
        if(!$summary){
            $summary = new BlockchainSummary();
            $summary->trees = (int) $blockchain->total_tx;
            $summary->save();
        }else{
            $summary->trees = (int) $blockchain->total_tx;
            $summary->update();
        }

        return response()->json($summary->fresh());
    }

    public function updateSummaryTrees(){
        $summary = BlockchainSummary::first();
        if(!$summary){
            $summary = new BlockchainSummary();
            $summary->save(request()->all());
        }else{
            $summary->update(request()->all());
        }
        return response()->json($summary->fresh());
    }
}
