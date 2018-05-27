<?php 

class GameController extends Controller{
	protected $model;

	public function __construct(){
		$this->model = new Game();
	}

	public function get_map($id){
		$map = $this->model->get_map($id)[0][1];
		$data = explode("\n",$map);
		$maps = [];
		for($i = 0; $i<count($data);$i++){
			$temp = [];
			$row = str_split($data[$i]);
			foreach($row as $r){
				if($r == '1'){
					array_push($temp, 0);
				} else if($r == '2'){
					array_push($temp, 1);
				} else {
					array_push($temp, rand(2,5));
				}
			}
			array_push($maps, $temp);
		}
		return json_encode(array('data'=>$maps));

	}

	public function get_star($id){
		$star = $this->model->get_map($id)[0][2];
		$data = explode("\n",$star);
		$stars = [];
		for($i = 0; $i<count($data);$i++){
			$temp = [];
			$row = str_split($data[$i]);
			foreach($row as $r){
				if($r == '1'){
					array_push($temp, 1);
				} else {
					array_push($temp, 0);
				}
			}
			array_push($stars, $temp);
		}
		return json_encode(array('data'=>$stars));
	}

	public function push(){
	    $star = $_POST['star'];
	    $step = $_POST['step'];
	    $room_id = $_POST['room_id'];
	    $user_id = $_POST['user_id'];
	    $level_id = $_POST['level_id'];
        echo $this->model->set_score($room_id,$user_id,$step,$level_id);
        echo $this->model->set_exp($user_id,$star);
    }

}