<?php
/**
 * Created by PhpStorm.
 * User: ferdinand
 * Date: 5/24/18
 * Time: 5:10 PM
 */

class Auth
{
    public static function login($email,$password){
        $query = "";
        $query.= "CALL sp_login('$email','$password');";
        $query.= "SELECT * FROM `user` WHERE email='".$email."';";

        $result = MySQL::MultiQuery($query);
        /*
         * If Login Success
         */
        if($result[0][0][0] == 1){
            Auth::set_session($result[1][0]);
            return true;
        }
        else{
            return false;
        }
    }

    public static function set_session($array){
        $_SESSION['user_id'] = $array['user_id'];
        $_SESSION['level'] = $array['level_level'];
        $_SESSION['username'] = $array['username'];
        $_SESSION['email'] = $array['email'];
        $_SESSION['exp'] = $array['exp'];
    }

    public static function get_session(){
        $result = [];
        if(isset($_SESSION['user_id'])){
            $result['user_id'] = $_SESSION['user_id'];
            $result['level'] = $_SESSION['level'];
            $result['username'] = $_SESSION['username'];
            $result['email'] = $_SESSION['email'];
            $result['exp'] = $_SESSION['exp'];
        }
        return $result;
    }

    public static function register($username,$email,$password){
        $query = "CALL sp_daftar('$username','$email','$password')";
        $result = MySQL::Query($query,true);
        if($result[0] == 1){ // success

        }
    }

    public static function user(){
        $res = Auth::get_session();
        if($res == []){
            // You're not Authenticate !
            return false;
        }
        else{
            // You're Authenticate;
            return $res;
        }
    }
}