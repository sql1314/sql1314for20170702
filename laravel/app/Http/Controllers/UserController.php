<?php
namespace App\Http\Controllers;
use Redis;
use App\Http\Controllers\Controller;

class UserController extends Controller{
    public function showProfile($id)
    {
        Redis::set('user',"user001");
        Redis::set('user',"user002");
        $user = Redis::get('user:profile:'.$id);
        echo 1;
        var_dump($user);die;
        return view('user.profile',['user' => $user]);
    }
}