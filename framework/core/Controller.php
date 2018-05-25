<?php
/**
 * Created by PhpStorm.
 * User: ferdinand
 * Date: 5/24/18
 * Time: 3:16 PM
 */

/*
 * Base Controller
 */
class Controller
{
    // Base Controller has a property called $loader, it is an instance of Loader Class
    protected $loader;

    public function __construct(){
        $this->loader = new Loader();
    }

}