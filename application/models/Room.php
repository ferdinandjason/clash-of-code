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
        $user_id = $_SESSION['user_id'];
        $query = "CALL sp_create_room($level,'$name','$password','$user_id');";
        MySQL::Query($query,false);
    }

    public function insert_to_room($room,$user_id,$password){
        $query = "CALL sp_insert_into_room($room,$user_id,'$password');";
        return MySQL::Query($query,true)[0][0];
    }

    public function get_all_room(){
        $query = "SELECT * FROM room_detail WHERE avaiable = 1;";
        return MySQL::Query($query,true);
    }

    public function get_room($user){
        $query = "SELECT room.user_id as user_id, room.room_id as room_id, room_detail.name as name FROM room INNER JOIN room_detail ON room.room_id = room_detail.room_id where room.user_id = $user ;";
        return MySQL::Query($query,true);
    }

    public static function is_joined_room($user_id,$room_id){
        $query = "SELECT fn_is_joined_room($room_id,$user_id);";
        return MySQL::Query($query,true)[0][0];
    }

    public function get_game($id){
        $query = "SELECT * from room_detail WHERE room_id = $id";
        return MySQL::Query($query,true)[0];
    }
}