<?php
/**
 * Created by PhpStorm.
 * User: ferdinand
 * Date: 5/24/18
 * Time: 2:53 PM
 */

class Framework
{
    public static function run(){
        self::init();
        self::autoload();
        self::routing();
    }

    /*
     * Initialization
     */
    private static function init(){
        /*
         * Define path constants
         */

        define("DS",DIRECTORY_SEPARATOR);
        define("ROOT",getcwd().DS);
        define("APP_PATH",ROOT.'application'.DS);
        define("FRAMEWORK_PATH",ROOT.'framework'.DS);
        define("PUBLIC_PATH", ROOT . "public" . DS);

        define("CONFIG_PATH", APP_PATH . "config" . DS);
        define("CONTROLLER_PATH", APP_PATH . "controllers" . DS);
        define("MODEL_PATH", APP_PATH . "models" . DS);
        define("VIEW_PATH", APP_PATH . "views" . DS);
        define("ROUTE_PATH", APP_PATH . "route" . DS);

        define("CORE_PATH", FRAMEWORK_PATH . "core" . DS);
        define('DB_PATH', FRAMEWORK_PATH . "database" . DS);
        define("LIB_PATH", FRAMEWORK_PATH . "libraries" . DS);
        define("HELPER_PATH", FRAMEWORK_PATH . "helpers" . DS);

        define("UPLOAD_PATH", PUBLIC_PATH . "uploads" . DS);

        /*
         * Define platform, controller, action
         * index.php?p=admin&c=good&a=add;
         */

        define("PLATFORM", isset($_REQUEST['p']) ? $_REQUEST['p'] : 'home');
        define("CONTROLLER", isset($_REQUEST['c']) ? $_REQUEST['c'] : 'Index');
        define("ACTION", isset($_REQUEST['a']) ? $_REQUEST['a'] : 'index');

        define("CURR_CONTROLLER_PATH", CONTROLLER_PATH . PLATFORM . DS);
        define("CURR_VIEW_PATH", VIEW_PATH . PLATFORM . DS);

        /*
         * Load core classes
         */

        require CORE_PATH . "Controller.php";
        require CORE_PATH . "Loader.php";
        require DB_PATH . "MySQL.php";
        require CORE_PATH . "Model.php";
        require CORE_PATH . "Router.php";
        require CORE_PATH . "Auth.php";
        require CORE_PATH . "View.php";

        /*
         * Load Helper Function
         */
        require HELPER_PATH . "helper.php";
        /*
         * Load configuration file
         */
        require CONFIG_PATH . "DB.php";

        /*
         * Start Session
         */

        session_start();

        /*
         * Display Errors
         */
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    /*
     * PHP built-in function
     * spl_autoload_register()
     *
     * Autoload class using custom load method
     */
    private static function autoload(){
        spl_autoload_register(array(__CLASS__,'load'));
    }

    /*
     * Autoload class based on their classname
     */
    private static function load($classname){
        if(substr($classname,-10) == "Controller"){
            // Controller
            require_once CONTROLLER_PATH."$classname.php";
        }
        else{
            // Model
            require_once  MODEL_PATH."$classname.php";
        }
    }

    private static function routing(){
        /*
         * Read the Routing
         */
        require ROUTE_PATH . "web.php";

    }
}