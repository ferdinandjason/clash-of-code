<?php
/**
 * Created by PhpStorm.
 * User: ferdinand
 * Date: 5/24/18
 * Time: 3:18 PM
 */

/*
 * Loader Class
 */
class Loader
{
    /*
     * Load library classes
     */
    public function library($lib){
        include LIB_PATH."$lib.php";
    }

    /*
     * Load helper functions.
     * Namefile = {}_helper.php
     */
    public function helper($helper){
        include HELPER_PATH."{$helper}_helper.php";
    }
}