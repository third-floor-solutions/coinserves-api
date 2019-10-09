<?php

namespace App\Http\Controllers\Api\Blockchain;

use App\Http\Controllers\Controller;
use App\Model\Blockchain;
use Illuminate\Http\Request;

class BlockchainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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
}
