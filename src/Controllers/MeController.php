<?php
class MeController
{
    private $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function profile()
    {
        AuthMiddleware::ensure();
        Helpers::view('app/profile');
    }

    public function me($params)
    {
        AuthMiddleware::ensure();
        $user = $this->users->findById(Auth::userId());
        if (!$user) {
            return Response::json(array('ok' => false, 'error' => array('code' => 'not_found', 'message' => 'User not found')), 404);
        }
        unset($user['password_hash']);
        return Response::json(array('ok' => true, 'data' => $user));
    }

    public function update($params)
    {
        AuthMiddleware::ensure();
        $data = Request::input();
        $errors = Validator::required(array('email', 'first_name', 'last_name', 'username'), $data);
        if (!empty($errors)) {
            return Response::json(array('ok' => false, 'error' => array('code' => 'validation_error', 'message' => 'Validation error', 'fields' => $errors)), 422);
        }
        $this->users->update(Auth::userId(), $data);
        return Response::json(array('ok' => true, 'data' => array('updated' => true)));
    }
}
