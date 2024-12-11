<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Session;
use Hash;


class RegisterBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-register-basic');
  }

  public function register(Request $request)
  {
    //dd($request->all());
    $request->validate([
      'username' => 'required|unique:users',
      'email' => 'required|email|unique:users',
      'password' => 'required|min:6',
    ]);


    User::create([
      'name' => $request->username,
      'email' => $request->email,
      'password' => Hash::make($request->password)
    ]);

    //dd($user);

    return redirect("/");
  }
}
