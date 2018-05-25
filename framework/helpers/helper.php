<?php

function pass($view_name,$array){
    $temp = [];
    foreach ($array as $key => $value){
        $_SESSION[$key]=$value;
        array_push($temp,$key);
    }
    $_SESSION[$view_name]=$temp;
}

function back(){
    header("Location: {$_SERVER['HTTP_REFERER']}");
    return 0;
}