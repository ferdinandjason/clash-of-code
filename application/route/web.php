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
Router::route('/room/play/(.+)+',function($id){
    Router::handle('RoomController@play',array($id));
});

Router::route('/login',function(){
    Router::handle('UserController@login');
});
Router::route('/register',function(){
    Router::handle('UserController@register');
});

Router::execute(substr($_SERVER['REQUEST_URI'],4));