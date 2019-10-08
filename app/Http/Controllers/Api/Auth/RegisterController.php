<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Api\UserResource;
use App\Model\User;

class RegisterController extends Controller
{
    //
    public function __invoke(RegisterRequest $request)
    {
        $user = new User();
        // $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save(); 

        // Todo send mail
        return new UserResource($user);
    }
}
