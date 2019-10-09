<?php

namespace App\Http\Controllers\Api\Auth;

use App\Model\Blockchain;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Model\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
class RegisterController extends Controller
{
    //
    public function __invoke(RegisterRequest $request)
    {
        $user = new User();
        $blockchain = new Blockchain();
        $client = new Client([
            'base_uri' => 'https://blockchain.info/',
            'timeout'  => 5.0,
        ]);
        // /*sample wallet address
        // *1AJbsFZ64EpEfS5UAjAfcUG8pH8Jn3rn1F
        // *1A8JiWcwvpY7tAopUkSnGuEYHmzGYfZPiq
        // *1MDUoxL1bGvMxhuoDYx6i11ePytECAk9QK
        // */
        try {
            $requestBlockChain = $client->request('GET', 'rawaddr/' . $request->wallet_address . '?limit=1');
        } catch (RequestException $e) {
            return response()->json(['message' => 'Wallet not found'],500);
        }
        ////Blockchain
        $response = $requestBlockChain->getBody();
        $obj = json_decode($response);
        $blockchain->id = $request->wallet_address;
        $blockchain->wallet_address = $request->wallet_address;
        $blockchain->wallet_type = $request->wallet_type;
        $blockchain->initial_tx = $obj->n_tx;
        $blockchain->cnsrv_n_tx = $obj->n_tx;
        $blockchain->save(); 


        ////User Profile
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->display_name = $request->display_name;
        $user->user_type = request("user_type", "member");
        $user->wallet_address = $request->wallet_address;

        $user->save(); 
        // Todo send mail
        return response()->json($user);
    }
}
