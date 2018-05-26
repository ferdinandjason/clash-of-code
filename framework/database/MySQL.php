<?php
/**
 * Created by PhpStorm.
 * User: ferdinand
 * Date: 5/24/18
 * Time: 3:22 PM
 */

/*
 * framework/database/MySQL.php
 * Database operation class
 */
class MySQL
{
    private static $DB;
    public static function Connect(){
        if(!isset(self::$DB)){
            self::$DB = new mysqli (DB_HOST, DB_USER, DB_PASS, DB_NAME);
        }
    }

    public static function Disconnect(){
        if(isset(self::$DB)){
            self::$DB->close();
        }
    }

    public static function Query($query,$output = false,$verbose = false){
        MySQL::Connect();
        $result = self::$DB->query($query);
        if($output){
            if($verbose) echo self::$DB->error;
            $temp = $result->fetch_all(MYSQLI_BOTH);
            return $temp;
        }
    }

    public static function MultiQuery($query){
        MySQL::Connect();
        $table = [];
        if(self::$DB->multi_query($query)){
            do{
                /* Store first result set */
                if($result = self::$DB->store_result()){
                    array_push($table,$result->fetch_all(MYSQLI_BOTH));
                    $result->close();
                }
            } while (self::$DB->more_results() && self::$DB->next_result());
        }
        return $table;
    }
}