<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Http\Resources\User as UserResource;
use App\UserCall;
use App\Http\Resources\UserCall as UserCallResource;




class LogisticController extends Controller
{
    public function index()
    {
        // $users= UserResource::collection(User::all());
        // return $users;
        // foreach ($users as $user) {
        //     return $user->id;
        // }
        //   User::chunk(50, function ($users) {
        //foreach ($users as $user) {
        //   return 'hello     ';
        //}
        //  });
        // $users = User::paginate();
        // return UserResource::collection($users);
        $calls = UserCall::all();
        return UserCallResource::collection($calls);



        
    }
    public function logistics()
    {
        return view('admin.logistics.index');
    }
}