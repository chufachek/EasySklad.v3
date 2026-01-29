<?php
class WarehousesController
{
    private $warehouses;

    public function __construct()
    {
        $this->warehouses = new WarehouseModel();
    }

    public function page()
    {
        AuthMiddleware::ensure();
        Helpers::view('app/warehouses');
    }

    public function index($params)
    {
        AuthMiddleware::ensure();
        $rows = $this->warehouses->allByCompany($params['companyId']);
        return Response::json(array('ok' => true, 'data' => $rows));
    }

    public function store($params)
    {
        AuthMiddleware::ensure();
        $data = Request::input();
        $id = $this->warehouses->create($params['companyId'], $data);
        return Response::json(array('ok' => true, 'data' => array('id' => $id)));
    }

    public function update($params)
    {
        AuthMiddleware::ensure();
        $data = Request::input();
        $this->warehouses->update($params['id'], $data);
        return Response::json(array('ok' => true, 'data' => array('updated' => true)));
    }

    public function delete($params)
    {
        AuthMiddleware::ensure();
        $this->warehouses->delete($params['id']);
        return Response::json(array('ok' => true, 'data' => array('deleted' => true)));
    }
}
