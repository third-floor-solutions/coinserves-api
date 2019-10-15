<?php

namespace App\Http\Controllers\Api\Blockchain;

use App\Http\Controllers\Controller;
use App\Model\Blockchain;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;

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
        $client = new Client([
            'base_uri' => 'https://blockchain.info/',
            'timeout'  => 5.0,
        ]);
        // /*sample wallet address
        // *1AJbsFZ64EpEfS5UAjAfcUG8pH8Jn3rn1F
        // *1A8JiWcwvpY7tAopUkSnGuEYHmzGYfZPiq
        // *1MDUoxL1bGvMxhuoDYx6i11ePytECAk9QK
        // *1Kr6QSydW9bFQG1mXiPNNu6WpJGmUa9i1g
        // */
        try {
            $requestBlockChain = $client->request('GET', 'rawaddr/' . $wallet_address . '?limit=1');
        } catch (RequestException $e) {
            return response()->json(['message' => 'Wallet not found'],500);
        }
        ////Blockchain
        ///need change
        $response = $requestBlockChain->getBody();
        $obj = json_decode($response);

        $blockchain = Blockchain::select(DB::raw('ABS(SUM(cnsrv_n_tx) - SUM(initial_tx)) AS total_tx'))->where('user_id', auth()->user()->id)->first();

        return response()->json($blockchain->total_tx);
        if(!$blockchain){
            return response()->json(['error'=>"404",'message'=>"wallet address not found"],404);
        }
        $tx = $obj->n_tx - $blockchain->initial_tx;
        $trees = floor($tx/3);
        $blockchain->cnsrv_n_tx = $obj->n_tx;
        $blockchain->trees = $trees;
        $blockchain->save();
        return $blockchain->fresh();
    }
}
