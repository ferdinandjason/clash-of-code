<?php
/**
 * Created by PhpStorm.
 * User: ferdinand
 * Date: 5/24/18
 * Time: 4:55 PM
 */

class Router
{
    private static $routes = array();

    private function __construct(){}
    private function __clone(){}

    public static function route($pattern,$callback){
        $pattern = '/^' . str_replace('/','\/',$pattern).'$/';
        self::$routes[$pattern] = $callback;
    }

    public static function execute($url){
        foreach(self::$routes as $pattern => $callback){
            if (preg_match($pattern, $url, $params)) {
                array_shift($params);
                if(count($params)){
                    $params = explode('/',$params[0]);    
                    $params = array(end($params));
                }
                else{
                    
                }
                
                return call_user_func_array($callback, array_values($params));
            }
        }
    }

    public static function handle($controller,$data = array()){
        define('NAME',0);
        define('METHOD',1);

        $controller = explode('@',$controller);
        return call_user_func_array(array(new $controller[NAME],$controller[METHOD]),$data);
    }
}