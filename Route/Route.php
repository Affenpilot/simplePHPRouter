<?php

namespace Route;

use Configuration\Configuration;

/**
 * Class Route.
 */
class Route
{
    /**
     * @var array
     */
    public static $routes = [];

    /**
     * @var array
     */
    public static $routes404 = [];

    /**
     * @var
     */
    public static $path;

    /**
     * Init routing
     */
    public static function init()
    {
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);

        self::$path = '';

        if(isset($parsed_url['path'])){
            self::$path = trim($parsed_url['path'],'/');
        }
    }

    /**
     * @param $expression
     * @param $function
     */
    public static function add($expression, $function)
    {
        array_push(self::$routes, [
            'expression'=> $expression,
            'function'  => $function
        ]);
    }

    /**
     * @param $function
     */
    public static function add404($function)
    {
        array_push(self::$routes404, $function);
    }

    /**
     * Run routing
     */
    public static function run()
    {
        $route_found = false;
        
        foreach(self::$routes as $route) {
            if(Configuration::get('basepath')) {
                //Add / if its not empty
                if($route['expression'] !=''){
                    $route['expression'] = '/'.$route['expression'];
                }
                $route['expression'] = '('.Configuration::get('basepath').')'.$route['expression'];
            }

            //Add 'find string start' automatically
            $route['expression'] = '^'.$route['expression'];
 
            //Add 'find string end' automatically
            $route['expression'] = $route['expression'].'$';

            //check match
            if(preg_match('#'.$route['expression'].'#',self::$path,$matches)){

                array_shift($matches);//Always remove first element. This contains the whole string
                
                if(Configuration::get('basepath')){
                    
                    array_shift($matches);//Remove Basepath

                }
                call_user_func_array($route['function'], $matches);
                $route_found = true;
            }
        }
        
        if(!$route_found){
            foreach(self::$routes404 as $route404){
                call_user_func_array($route404, Array(self::$path));
            }
        }
    }
}
