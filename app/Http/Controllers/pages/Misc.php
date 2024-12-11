<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Documentation;
use Illuminate\Http\Request;

class Misc extends Controller
{
  public function error()
  {
    return view('content.pages.pages-misc-error');
  }

  public function under_maintenance()
  {
    return view('content.pages.pages-misc-under-maintenance');
  }

  public function privacy_policy()
  {
    $privacy_policy = Documentation::privacy_policy('ar');
    return view('content.pages.pages-misc-privacy-policy')
    //->with('privacy_policy',nl2br($privacy_policy->content,false));
    ->with('privacy_policy',$privacy_policy->content);
  }
}
