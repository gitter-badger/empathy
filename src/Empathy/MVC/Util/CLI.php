<?php

namespace Empathy\MVC\Util;

/* based on Util/Test.php
   (which is currently outside of namespacing)

   usage: (from application subfolder. eg. '/scripts'.)
   // get into application-like context...
   include 'Empathy/Empathy.php';
   $boot = new Empathy(realpath(dirname(realpath(__FILE__)).'/../'), true);
   // make admin
   \Empathy\Session::up();
   \Empathy\Session::set('user_id', 2);
   $output = \Empathy\Util\CLI::request($boot, $data['url']);
   // print output etc...
   */

class CLI
{

    // new function
    private static function realMicrotime()
    {
        list($micro, $seconds) = explode(' ', microtime());

        return ((float) $micro + (float) $seconds);
    }

    public static function request($e, $uri, $return_time=true, $capture_output=true)
    {
        if ($capture_output) {
            ob_start();
        }

        $t_request_start = self::realMicrotime();
        $_SERVER['REQUEST_URI'] = $uri;

        $e->beginDispatch();
        $t_request_finish = self::realMicrotime();

        if ($capture_output) {
            $response = ob_get_contents();
            ob_end_clean();
        }

        $t_elapsed = ($t_request_finish - $t_request_start);
        $t_elapsed = number_format($t_elapsed, 4);

        // reset super globals
        $_GET = array();
        $_POST = array();

        if ($return_time === true) {
            return $t_elapsed;
        } elseif ($capture_output) {
            return $response;
        } else {
            return 1;
        }
    }
}
