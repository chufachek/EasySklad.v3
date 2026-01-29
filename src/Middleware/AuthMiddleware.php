<?php
class AuthMiddleware
{
    public static function ensure()
    {
        if (!Auth::check()) {
            if (strpos(Request::path(), '/api') === 0) {
                Response::json(array('ok' => false, 'error' => array('code' => 'unauthorized', 'message' => 'Unauthorized')), 401);
                exit;
            }
            Response::redirect('/login');
        }
    }
}
