<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;

class SimpleLoginController extends Controller
{
    //
    function vlogin(Request $request){
        $request->validate([
            'user_id' => 'required|string|max:20',
            'password' => 'required|string|max:20'
        ]);
        $input = $request->only('user_id', 'password');
        $user_id = $input['user_id'];
        $password = $input['password'];
        /*$user_id = $request->input("user_id");
        $password = $request->input("password");*/

        $login = Member::where('user_id', $user_id)->where('password', $password)->first();

        if(isset($login)){
            session()->put("simple_auth", $user_id);
            //session()->put("simple_auth", true);
            return redirect("/member/index");
        }

        return redirect("/member/form")->withErrors([
            "login" => "ユーザーIDまたはパスワードが違います"
        ]);
    }
}
