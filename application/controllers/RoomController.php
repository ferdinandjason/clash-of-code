<?php
/**
 * Created by PhpStorm.
 * User: ferdinand
 * Date: 5/24/18
 * Time: 9:00 PM
 */

class RoomController extends Controller
{
    protected $model;

    public function __construct()
    {
        $room = new Room();
        $this->model = $room;
    }

    public function create(){
        $this->model->create_room($_POST['name'],$_POST['password']);
    }

    public function join(){
        $status = $this->model->insert_to_room($_POST['roomjoin'],$_SESSION['user_id'],$_POST['password']);
    }

    public function play($id){
        $room = $this->model->get_game($id);
        return View::make('game',compact('room'));
    }

}