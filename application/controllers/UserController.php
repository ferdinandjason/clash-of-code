<?php
/**
 * Created by PhpStorm.
 * User: ferdinand
 * Date: 5/24/18
 * Time: 10:01 PM
 */

class UserController extends Controller
{
    protected $model;

    public function __construct()
    {
        $user = new User();
        $this->model = $user;
    }

    public function login(){
        $status = Auth::login($_POST['email'],$_POST['password']);
        return back();
    }

    public function register(){
        if($_POST['password'] == $_POST['password-repeat']){
            Auth::daftar($_POST['username'],$_POST['email'],$_POST['password']);
        }
        return back();
    }

}