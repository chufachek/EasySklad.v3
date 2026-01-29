<?php
class CategoriesController
{
    private $categories;

    public function __construct()
    {
        $this->categories = new CategoryModel();
    }

    public function page()
    {
        AuthMiddleware::ensure();
        Helpers::view('app/categories');
    }

    public function index($params)
    {
        AuthMiddleware::ensure();
        $rows = $this->categories->allByCompany($params['companyId']);
        return Response::json(array('ok' => true, 'data' => $rows));
    }

    public function store($params)
    {
        AuthMiddleware::ensure();
        $data = Request::input();
        $id = $this->categories->create($params['companyId'], $data);
        return Response::json(array('ok' => true, 'data' => array('id' => $id)));
    }

    public function update($params)
    {
        AuthMiddleware::ensure();
        $data = Request::input();
        $this->categories->update($params['id'], $data);
        return Response::json(array('ok' => true, 'data' => array('updated' => true)));
    }

    public function delete($params)
    {
        AuthMiddleware::ensure();
        $this->categories->delete($params['id']);
        return Response::json(array('ok' => true, 'data' => array('deleted' => true)));
    }
}
