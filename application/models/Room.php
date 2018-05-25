<?php
/**
 * Created by PhpStorm.
 * User: ferdinand
 * Date: 5/24/18
 * Time: 5:54 PM
 */

class Room extends Model
{
    public function get_max_level(){
        $query = "SELECT fn_get_max_level();";
        $result = MySQL::Query($query,true);
        return rand(0,$result[0][0]);
    }

    public function create_room($name,$password){
        $level = $this->get_max_level();
        $query = "CALL sp_create_room($level,$name,$password);";
        MySQL::Query($query,false);
    }

    public function insert_to_room($room,$user_id){
        $query = "CALL sp_insert_into_room($room,$user_id);";
        MySQL::Query($query,false);
    }

    public function get_all_room(){
        $query = "SELECT * FROM room_detail WHERE avaiable = 1;";
        return MySQL::Query($query,true);
    }

    public function get_room($user){
        $query = "SELECT * FROM room WHERE user_id = $user;";
        return MySQL::Query($query,true);
    }

    public static function is_joined_room($user_id,$room_id){
        $query = "SELECT fn_is_joined_room($room_id,$user_id);";
        return MySQL::Query($query,true)[0];
    }
}