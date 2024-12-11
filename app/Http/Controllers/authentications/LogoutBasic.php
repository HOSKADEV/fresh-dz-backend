<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;

class LogoutBasic extends Controller
{
  public function logout(){

    Session::flush();
    Auth::logout();
    return redirect('/auth/login-basic');

  }
}
