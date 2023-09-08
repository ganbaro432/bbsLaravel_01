<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimpleLogoutController extends Controller
{
    //
    function vlogout(){
        session()->forget("simple_auth");
        return redirect("/member/form");
    }
}
