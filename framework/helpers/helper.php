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

function find($highscore,$level_id){
    for($i=0;$i<count($highscore);$i++){
        if($highscore[$i]['level_id'] == $level_id){
            return $highscore[$i]['score'];
        }
    }
    return 'No highscore in this level!';
}