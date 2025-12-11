<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Distributor;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // super_admin only
    public function store(Request $r){
        $r->validate(['name'=>'required','email'=>'required|email|unique:users','role'=>'required']);
        $password = Str::random(8);
        $user = User::create([
            'name'=>$r->name,
            'email'=>$r->email,
            'phone'=>$r->phone,
            'password'=>bcrypt($password),
        ]);
        $user->assignRole($r->role);

        if($r->role === 'distributor'){
            Distributor::create(['user_id'=>$user->id,'wilaya'=>$r->wilaya]);
        }

        return response()->json(['user'=>$user,'temporary_password'=>$password],201);
    }

    public function index(Request $r){
        $users = User::with('roles')->paginate(20);
        return response()->json($users);
    }
}
