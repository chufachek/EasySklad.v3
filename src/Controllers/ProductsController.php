<?php
class ProductsController
{
    private $products;

    public function __construct()
    {
        $this->products = new ProductModel();
    }

    public function page()
    {
        AuthMiddleware::ensure();
        Helpers::view('app/products');
    }

    public function index($params)
    {
        AuthMiddleware::ensure();
        $rows = $this->products->listByWarehouse($params['warehouseId'], Request::input());
        return Response::json(array('ok' => true, 'data' => $rows));
    }

    public function store($params)
    {
        AuthMiddleware::ensure();
        $data = Request::input();
        $id = $this->products->create($params['warehouseId'], $data);
        return Response::json(array('ok' => true, 'data' => array('id' => $id)));
    }

    public function update($params)
    {
        AuthMiddleware::ensure();
        $data = Request::input();
        $this->products->update($params['id'], $data);
        return Response::json(array('ok' => true, 'data' => array('updated' => true)));
    }

    public function search($params)
    {
        AuthMiddleware::ensure();
        $input = Request::input();
        $warehouseId = isset($input['warehouseId']) ? $input['warehouseId'] : null;
        $query = isset($input['q']) ? $input['q'] : '';
        $rows = $this->products->search($warehouseId, $query);
        return Response::json(array('ok' => true, 'data' => $rows));
    }
}
