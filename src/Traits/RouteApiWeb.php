<?php

namespace App\Traits;

trait RouteApiWeb
{

    protected static function get_route_type($url)
    {

        if (str_contains($url, '/api/')) {
            $type = 'API';
        } else {
            $type = 'WEB';
        }
        return $type;
    }

}