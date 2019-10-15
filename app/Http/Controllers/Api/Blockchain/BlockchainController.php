<?php

namespace App\Http\Controllers\Api\Blockchain;

use App\Http\Controllers\Controller;
use App\Model\Blockchain;
use App\Model\BlockchainResult;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;
use App\Repository\BlockchainRepository;

class BlockchainController extends Controller
{
    protected $blockchainRepository;

    public function __construct(BlockchainRepository $blockchain)
    {
        $this->middleware('auth');
        $this->blockchainRepository = $blockchain;
    }

    public function getBlockchain($wallet_address){
        $blockchain = Blockchain::where('wallet_address',$wallet_address)->first();
        if(!$blockchain){
            return response()->json(['error'=>"404",'message'=>"wallet address not found"], 404);
        }
        return response()->json($blockchain);
    }

    public function getAllBlockchain(){
        $blockchains = Blockchain::orderBy('updated_at', "desc")->paginate(request()->input('per_page'));
        if(!$blockchains){
            return response()->json(['error'=>"404",'message'=>"no wallet address found"], 404);
        }
        return response()->json($blockchains);
    }

    public function getAllArchivedBlockchain(){
        $blockchains = Blockchain::onlyTrashed()->orderBy('updated_at', "desc")->paginate(request()->input('per_page'));
        if(!$blockchains){
            return response()->json(['error'=>"404",'message'=>"no wallet address found"], 404);
        }
        return response()->json($blockchains);
    }

    public function updateBlockchain($wallet_address){
        $this->validate(request(), [
            "cnsrv_n_tx" => "required"
        ]);
        $blockchain = Blockchain::where('wallet_address', $wallet_address)->first();
        if(!$blockchain){
            return response()->json(['error'=>"404",'message'=>"wallet address not found"],404);
        }
        if(request()->initial_tx){
            $blockchain->initial_tx = request()->initial_tx;
        }
        $blockchain->cnsrv_n_tx = request()->cnsrv_n_tx;
        $blockchain->save();
        return $blockchain->fresh();
    }

    public function deleteBlockchain($wallet_address){
        $blockchain = Blockchain::where('wallet_address', $wallet_address)->first();
        if(!$blockchain){
            return response()->json(['error'=>"404",'message'=>"wallet address not found"],404);
        }
        $blockchain->delete();
        return $blockchain->fresh();
    }

    public function restoreBlockchain($wallet_address){
        $blockchain = Blockchain::onlyTrashed()->where('wallet_address', $wallet_address)->first();
        if(!$blockchain){
            return response()->json(['error'=>"404",'message'=>"wallet address not found"],404);
        }
        $blockchain->restore();
        return $blockchain->fresh();
    }

    public function getBlockchainTransaction($wallet_address){
        return $this->blockchainRepository->getTransactions($wallet_address);
    }

    public function getBlockchainTransactionByUserId($user_id){
        $transactions = $this->blockchainRepository->getTransactionsByUserId($user_id);
        $blockchain = Blockchain::select(DB::raw('ABS(SUM(cnsrv_n_tx) - SUM(initial_tx)) AS total_tx'))
                ->where('user_id', $user_id)->first();
        $result = new BlockchainResult();
        $result->total_tx = $blockchain->total_tx;
        $result->trees = floor(((int)$blockchain->total_tx)/3);
        $result->tx = $transactions;

        return response()->json($result);
    }
}
