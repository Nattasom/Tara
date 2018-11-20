<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\CustomModel\UserModel;

class Login extends Controller
{
    private $userObj;
    function __construct(){
        $this->userObj = new UserModel();
    }
    function index(Request $request){
        // $request->session()->forget('userinfo');
        // $request->session()->flush();
        return view('login',["username"=>'',"status"=>'']);
    }
    function login(Request $request){ //only post
        $username = $request->input("loginUsername");
        $password = $request->input("loginPassword");
        $resUser = $this->userObj->login($username,$password);
        $status = "01";
        if($resUser["status"]=="01"){
            $status = "success";
            $request->session()->put('userinfo', $resUser["user"]);
            return redirect()->route('page.home');
        }
        else{
            $status = "fail";
        }
        return view('login',["username"=>$username,"status"=>$status]);
    }
    function logout(Request $request){
        $request->session()->forget('userinfo');
        $request->session()->flush();
        return Redirect::to('login');
    }
}
