<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Api\UserResource;
use App\Model\User;
use GuzzleHttp\Client;

class RegisterController extends Controller
{
    //
    public function __invoke(RegisterRequest $request)
    {
        $user = new User();
        $client = new Client([
            'base_uri' => 'https://blockchain.info/',
            'timeout'  => 5.0,
        ]);
        /*sample wallet address
        *1AJbsFZ64EpEfS5UAjAfcUG8pH8Jn3rn1F
        *1A8JiWcwvpY7tAopUkSnGuEYHmzGYfZPiq
        *1MDUoxL1bGvMxhuoDYx6i11ePytECAk9QK
        */
        $requestBlockChain = $client->request('GET', 'rawaddr/' . $request->wallet_address . '?limit=1');
        $response = $requestBlockChain->getBody();
        $obj = json_decode($response);
        $user->wallet_address = $obj->address;
        $user->initial_tx = $obj->n_tx;
        $user->cnsrv_n_tx = $obj->n_tx;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->display_name = $request->display_name;
        $user->user_type = $request->user_type;
        $user->wallet_type = $request->wallet_type;
        $user->wallet_address = $request->wallet_address;
        $user->save(); 

        // Todo send mail
        return new UserResource($user);
    }
}
