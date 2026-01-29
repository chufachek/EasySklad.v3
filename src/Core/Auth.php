<?php
class Auth
{
    public static function userId()
    {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }

    public static function check()
    {
        return self::userId() !== null;
    }

    public static function login($userId)
    {
        $_SESSION['user_id'] = $userId;
    }

    public static function logout()
    {
        unset($_SESSION['user_id']);
        session_destroy();
    }
}
