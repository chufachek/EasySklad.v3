<?php
class ServicesController
{
    private $services;

    public function __construct()
    {
        $this->services = new ServiceModel();
    }

    public function page()
    {
        AuthMiddleware::ensure();
        Helpers::view('app/services');
    }

    public function index($params)
    {
        AuthMiddleware::ensure();
        $rows = $this->services->allByCompany($params['companyId']);
        return Response::json(array('ok' => true, 'data' => $rows));
    }

    public function store($params)
    {
        AuthMiddleware::ensure();
        $data = Request::input();
        $id = $this->services->create($params['companyId'], $data);
        return Response::json(array('ok' => true, 'data' => array('id' => $id)));
    }

    public function update($params)
    {
        AuthMiddleware::ensure();
        $data = Request::input();
        $this->services->update($params['id'], $data);
        return Response::json(array('ok' => true, 'data' => array('updated' => true)));
    }

    public function delete($params)
    {
        AuthMiddleware::ensure();
        $this->services->delete($params['id']);
        return Response::json(array('ok' => true, 'data' => array('deleted' => true)));
    }
}
