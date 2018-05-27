<?php 

class Game extends Model{
	public function get_map($id){
		$query = "SELECT * FROM level where level_id = $id ";
		return MySQL::Query($query,true);
	}

	public function set_score($room_id,$user_id,$score,$level_id){
	    $query = "CALL sp_set_score($room_id,$user_id,$score,$level_id)";
	    return MySQL::Query($query,false);
    }

    public function set_exp($user_id,$star){
        $query = "CALL sp_set_exp($user_id,$star)";
        return MySQL::Query($query,false);
    }
}