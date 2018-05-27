<?php
/**
 * Created by PhpStorm.
 * User: ferdinand
 * Date: 5/24/18
 * Time: 5:31 PM
 */

Router::route('/',function(){
    $room_control = new Room();
    return View::make('home',compact('room_control'));
});

Router::route('/room/create',function(){
    Router::handle('RoomController@create');
});

Router::route('/room/join',function(){
    Router::handle('RoomController@join');
});
Router::route('/room/play/(.+)',function($id){
    Router::handle('RoomController@play',array($id));
});

Router::route('/login',function(){
    Router::handle('UserController@login');
});

Router::route('/register',function(){
    Router::handle('UserController@register');
});

Router::route('/game/star/(\d+)',function($id){
	echo Router::handle('GameController@get_star',array($id));
});
Router::route('/game/map/(\d+)',function($id){
	echo Router::handle('GameController@get_map',array($id));
});

Router::route('/game/room/clear',function(){
   Router::handle('GameController@push');
});

Router::route('/room/rank/(.+)',function($id){
    echo Router::handle('RoomController@get_rank',array($id));
});

Router::execute(substr($_SERVER['REQUEST_URI'],4));