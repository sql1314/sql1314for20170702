<?php
namespace App\Http\Controllers;
use Redis;
use App\Http\Controllers\Controller;

class UserController extends Controller{
    public function showProfile($id)
    {
        Redis::set('user:profile:1',"user001");
        Redis::set('user:profile:2',"user002");
        $user = Redis::get('user:profile:'.$id);
        echo 1;
        var_dump($user);
        return view('user.profile',['user' => $user]);
    }
}