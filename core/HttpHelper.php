<?php

class HttpHelper
{
    public static function applyCacheHeaders($statusCode) {
        switch ($statusCode) {
            case 200:
                header('Cache-Control: public, max-age=3600');
                break;
            case 404:
                header('Cache-Control: public, max-age=300');
                break;
            case 301:
            case 308:
                header('Cache-Control: public, max-age=86400');
                break;
            default:
                header('Cache-Control: no-store, no-cache, must-revalidate');
                break;
        }
    }
}