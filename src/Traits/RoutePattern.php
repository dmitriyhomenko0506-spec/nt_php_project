<?php


namespace App\Traits;

trait RoutePattern
{

    protected static function get_route_pattern($url)
    {

        $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $url);
        $pattern = '#^' . $pattern . '$#';
        return $pattern;
    }

}
