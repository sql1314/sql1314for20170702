<?php
namespace App\Http\Controllers;
use Redis;
use App\Http\Controllers\Controller;

class UserController extends Controller{
    public function showProfile($id)
    {
        $user = Redis::get('user:profile:'.$id);
        return view('user.profile',['user' => $user]);
    }
}