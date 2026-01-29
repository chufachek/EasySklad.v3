<?php
class ProductModel
{
    public function listByWarehouse($warehouseId, $params)
    {
        $pdo = Db::connection();
        $search = isset($params['search']) ? '%' . $params['search'] . '%' : '%';
        $categoryId = isset($params['category_id']) && $params['category_id'] !== '' ? $params['category_id'] : null;
        $limit = isset($params['limit']) ? (int)$params['limit'] : 20;
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $offset = ($page - 1) * $limit;

        if ($categoryId) {
            $stmt = $pdo->prepare('SELECT p.*, ps.qty FROM products p LEFT JOIN product_stocks ps ON ps.product_id = p.id AND ps.warehouse_id = p.warehouse_id WHERE p.warehouse_id = ? AND p.name LIKE ? AND p.category_id = ? ORDER BY p.id DESC LIMIT ? OFFSET ?');
            $stmt->execute(array($warehouseId, $search, $categoryId, $limit, $offset));
        } else {
            $stmt = $pdo->prepare('SELECT p.*, ps.qty FROM products p LEFT JOIN product_stocks ps ON ps.product_id = p.id AND ps.warehouse_id = p.warehouse_id WHERE p.warehouse_id = ? AND p.name LIKE ? ORDER BY p.id DESC LIMIT ? OFFSET ?');
            $stmt->execute(array($warehouseId, $search, $limit, $offset));
        }
        return $stmt->fetchAll();
    }

    public function search($warehouseId, $query)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('SELECT p.*, ps.qty FROM products p LEFT JOIN product_stocks ps ON ps.product_id = p.id AND ps.warehouse_id = p.warehouse_id WHERE p.warehouse_id = ? AND (p.name LIKE ? OR p.sku LIKE ?) ORDER BY p.name ASC LIMIT 20');
        $q = '%' . $query . '%';
        $stmt->execute(array($warehouseId, $q, $q));
        return $stmt->fetchAll();
    }

    public function create($warehouseId, $data)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('INSERT INTO products (warehouse_id, category_id, sku, name, price, cost, unit, min_stock, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute(array(
            $warehouseId,
            $data['category_id'],
            $data['sku'],
            $data['name'],
            $data['price'],
            $data['cost'],
            $data['unit'],
            $data['min_stock']
        ));
        $productId = $pdo->lastInsertId();
        $stmt = $pdo->prepare('INSERT INTO product_stocks (product_id, warehouse_id, qty) VALUES (?, ?, 0)');
        $stmt->execute(array($productId, $warehouseId));
        return $productId;
    }

    public function update($id, $data)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('UPDATE products SET category_id = ?, sku = ?, name = ?, price = ?, cost = ?, unit = ?, min_stock = ? WHERE id = ?');
        $stmt->execute(array(
            $data['category_id'],
            $data['sku'],
            $data['name'],
            $data['price'],
            $data['cost'],
            $data['unit'],
            $data['min_stock'],
            $id
        ));
        return true;
    }
}
