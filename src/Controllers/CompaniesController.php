<?php
class CompaniesController
{
    private $companies;

    public function __construct()
    {
        $this->companies = new CompanyModel();
    }

    public function company()
    {
        AuthMiddleware::ensure();
        Helpers::view('app/company');
    }

    public function index($params)
    {
        AuthMiddleware::ensure();
        $rows = $this->companies->allByOwner(Auth::userId());
        return Response::json(array('ok' => true, 'data' => $rows));
    }

    public function store($params)
    {
        AuthMiddleware::ensure();
        $data = Request::input();
        $errors = Validator::required(array('name'), $data);
        if (!empty($errors)) {
            return Response::json(array('ok' => false, 'error' => array('code' => 'validation_error', 'message' => 'Validation error', 'fields' => $errors)), 422);
        }
        $id = $this->companies->create(Auth::userId(), $data);
        return Response::json(array('ok' => true, 'data' => array('id' => $id)));
    }

    public function update($params)
    {
        AuthMiddleware::ensure();
        $data = Request::input();
        $this->companies->update($params['id'], Auth::userId(), $data);
        return Response::json(array('ok' => true, 'data' => array('updated' => true)));
    }
}
