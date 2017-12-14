<?php

namespace Main\Utils;

use Core\Session;

abstract class CSRF
{
    public static function generateFormToken()
    {
        $token = password_hash(uniqid(time(), true), PASSWORD_BCRYPT);
        Session::set('csrf_token',$token);
        return $token;
    }

    public static function verifyFormToken($formToken)
    {
        $sessionToken=Session::get('csrf_token');
        if (!isset($sessionToken)||!isset($formToken)||$sessionToken!==$formToken) {
            return false;
        }
        return true;
    }
    public static function getToken()
    {
        return Session::get('csrf_token');
    }
}