<?php
class IncomeController
{
    private $inventory;

    public function __construct()
    {
        $this->inventory = new InventoryService();
    }

    public function page()
    {
        AuthMiddleware::ensure();
        Helpers::view('app/income');
    }

    public function list($params)
    {
        AuthMiddleware::ensure();
        $pdo = Db::connection();
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;
        $stmt = $pdo->prepare('SELECT * FROM incomes WHERE warehouse_id = ? ORDER BY id DESC LIMIT ? OFFSET ?');
        $stmt->execute(array($params['warehouseId'], $limit, $offset));
        $rows = $stmt->fetchAll();
        return Response::json(array('ok' => true, 'data' => $rows));
    }

    public function store($params)
    {
        AuthMiddleware::ensure();
        $data = Request::input();
        try {
            $id = $this->inventory->createIncome($params['warehouseId'], $data);
            return Response::json(array('ok' => true, 'data' => array('id' => $id)));
        } catch (Exception $e) {
            return Response::json(array('ok' => false, 'error' => array('code' => 'income_error', 'message' => $e->getMessage())), 422);
        }
    }
}
