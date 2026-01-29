<?php
class OrderModel
{
    public function listByWarehouse($warehouseId, $params)
    {
        $pdo = Db::connection();
        $status = isset($params['status']) && $params['status'] !== '' ? $params['status'] : null;
        $limit = isset($params['limit']) ? (int)$params['limit'] : 20;
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $offset = ($page - 1) * $limit;

        if ($status) {
            $stmt = $pdo->prepare('SELECT * FROM orders WHERE warehouse_id = ? AND status = ? ORDER BY id DESC LIMIT ? OFFSET ?');
            $stmt->execute(array($warehouseId, $status, $limit, $offset));
        } else {
            $stmt = $pdo->prepare('SELECT * FROM orders WHERE warehouse_id = ? ORDER BY id DESC LIMIT ? OFFSET ?');
            $stmt->execute(array($warehouseId, $limit, $offset));
        }
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->execute(array($status, $id));
        return true;
    }
}
