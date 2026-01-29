<?php
class AuthController
{
    private $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function showLogin()
    {
        Helpers::render('login', array('flash' => Helpers::flash('success')));
    }

    public function showRegister()
    {
        Helpers::render('register');
    }

    public function register()
    {
        $data = Request::input();
        $errors = Validator::required(array('email', 'password', 'first_name', 'last_name', 'username'), $data);
        if (!empty($errors)) {
            Helpers::flash('error', 'Заполните обязательные поля');
            Response::redirect('/register');
        }
        $existing = $this->users->findByEmail($data['email']);
        if ($existing) {
            Helpers::flash('error', 'Email уже зарегистрирован');
            Response::redirect('/register');
        }
        $userId = $this->users->create(array(
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'balance' => 0,
            'tariff' => 'Free'
        ));
        Helpers::flash('success', 'Регистрация успешна, войдите в аккаунт');
        Response::redirect('/login');
    }

    public function login()
    {
        $data = Request::input();
        $errors = Validator::required(array('email', 'password'), $data);
        if (!empty($errors)) {
            Helpers::flash('error', 'Неверные данные');
            Response::redirect('/login');
        }
        $user = $this->users->findByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user['password_hash'])) {
            Helpers::flash('error', 'Неверный email или пароль');
            Response::redirect('/login');
        }
        Auth::login($user['id']);
        Response::redirect('/app/dashboard');
    }

    public function logout()
    {
        Auth::logout();
        Response::redirect('/login');
    }

    public function apiRegister($params)
    {
        $data = Request::input();
        $errors = Validator::required(array('email', 'password', 'first_name', 'last_name', 'username'), $data);
        if (!empty($errors)) {
            return Response::json(array('ok' => false, 'error' => array('code' => 'validation_error', 'message' => 'Validation error', 'fields' => $errors)), 422);
        }
        $existing = $this->users->findByEmail($data['email']);
        if ($existing) {
            return Response::json(array('ok' => false, 'error' => array('code' => 'email_taken', 'message' => 'Email already exists')), 409);
        }
        $userId = $this->users->create(array(
            'email' => $data['email'],
            'password_hash' => password_hash($data['password'], PASSWORD_BCRYPT),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'username' => $data['username'],
            'balance' => 0,
            'tariff' => 'Free'
        ));
        return Response::json(array('ok' => true, 'data' => array('id' => $userId)));
    }

    public function apiLogin($params)
    {
        $data = Request::input();
        $errors = Validator::required(array('email', 'password'), $data);
        if (!empty($errors)) {
            return Response::json(array('ok' => false, 'error' => array('code' => 'validation_error', 'message' => 'Validation error', 'fields' => $errors)), 422);
        }
        $user = $this->users->findByEmail($data['email']);
        if (!$user || !password_verify($data['password'], $user['password_hash'])) {
            return Response::json(array('ok' => false, 'error' => array('code' => 'invalid_credentials', 'message' => 'Invalid credentials')), 401);
        }
        Auth::login($user['id']);
        return Response::json(array('ok' => true, 'data' => array('id' => $user['id'])));
    }

    public function apiLogout($params)
    {
        Auth::logout();
        return Response::json(array('ok' => true, 'data' => array('logout' => true)));
    }
}
