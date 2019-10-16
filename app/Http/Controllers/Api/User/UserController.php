<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function getAllUsers(){
        $order_by = explode(':',request('order_by', 'updated_at:desc'));
        $where = explode(':',request('where','user_type:'));

        $AllUsers = User::where($where[0], 'like', '%' . $where[1])
            ->where($where[0], 'like', $where[1] . '%')
            ->orderBy($order_by[0], count($order_by) != 2 ? "desc" : $order_by[1])
            ->paginate(request('per_page', 24));

        return response()->json($AllUsers);
    }

    public function getUser($user_id){
        $user = User::findOrFail($user_id);

        return $user;
    }

    public function userUpdate($user_id){
        $user = User::findOrFail($user_id);
        $user->update(request()->all());
        return $user->fresh();
    }
}
