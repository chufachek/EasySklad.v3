<?php
class OrdersController
{
    private $inventory;
    private $orders;

    public function __construct()
    {
        $this->inventory = new InventoryService();
        $this->orders = new OrderModel();
    }

    public function page()
    {
        AuthMiddleware::ensure();
        Helpers::view('app/orders');
    }

    public function list($params)
    {
        AuthMiddleware::ensure();
        $rows = $this->orders->listByWarehouse($params['warehouseId'], Request::input());
        return Response::json(array('ok' => true, 'data' => $rows));
    }

    public function store($params)
    {
        AuthMiddleware::ensure();
        $data = Request::input();
        try {
            $id = $this->inventory->createOrder($params['warehouseId'], $data);
            return Response::json(array('ok' => true, 'data' => array('id' => $id)));
        } catch (Exception $e) {
            return Response::json(array('ok' => false, 'error' => array('code' => 'order_error', 'message' => $e->getMessage())), 422);
        }
    }

    public function updateStatus($params)
    {
        AuthMiddleware::ensure();
        $data = Request::input();
        $this->orders->updateStatus($params['id'], $data['status']);
        return Response::json(array('ok' => true, 'data' => array('updated' => true)));
    }
}
