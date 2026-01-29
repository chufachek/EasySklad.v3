<?php
class InventoryService
{
    public function createIncome($warehouseId, $data)
    {
        $pdo = Db::connection();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('INSERT INTO incomes (warehouse_id, supplier, date, total_cost, created_at) VALUES (?, ?, ?, ?, NOW())');
            $stmt->execute(array($warehouseId, $data['supplier'], $data['date'], $data['total_cost']));
            $incomeId = $pdo->lastInsertId();

            foreach ($data['items'] as $item) {
                $stmt = $pdo->prepare('INSERT INTO income_items (income_id, product_id, qty, cost) VALUES (?, ?, ?, ?)');
                $stmt->execute(array($incomeId, $item['product_id'], $item['qty'], $item['cost']));

                $stmt = $pdo->prepare('UPDATE product_stocks SET qty = qty + ? WHERE product_id = ? AND warehouse_id = ?');
                $stmt->execute(array($item['qty'], $item['product_id'], $warehouseId));

                $stmt = $pdo->prepare('INSERT INTO stock_movements (warehouse_id, product_id, type, qty, cost, ref_type, ref_id, created_at) VALUES (?, ?, "in", ?, ?, "income", ?, NOW())');
                $stmt->execute(array($warehouseId, $item['product_id'], $item['qty'], $item['cost'], $incomeId));
            }

            $pdo->commit();
            return $incomeId;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function createOrder($warehouseId, $data)
    {
        $pdo = Db::connection();
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare('INSERT INTO orders (warehouse_id, customer_name, payment_method, discount, total, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())');
            $stmt->execute(array(
                $warehouseId,
                $data['customer_name'],
                $data['payment_method'],
                $data['discount'],
                $data['total'],
                $data['status']
            ));
            $orderId = $pdo->lastInsertId();

            foreach ($data['items'] as $item) {
                $stmt = $pdo->prepare('SELECT qty FROM product_stocks WHERE product_id = ? AND warehouse_id = ? FOR UPDATE');
                $stmt->execute(array($item['product_id'], $warehouseId));
                $stock = $stmt->fetch();
                if (!$stock || $stock['qty'] < $item['qty']) {
                    throw new Exception('Insufficient stock');
                }

                $stmt = $pdo->prepare('INSERT INTO order_items (order_id, product_id, qty, price, total) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute(array($orderId, $item['product_id'], $item['qty'], $item['price'], $item['total']));

                $stmt = $pdo->prepare('UPDATE product_stocks SET qty = qty - ? WHERE product_id = ? AND warehouse_id = ?');
                $stmt->execute(array($item['qty'], $item['product_id'], $warehouseId));

                $stmt = $pdo->prepare('INSERT INTO stock_movements (warehouse_id, product_id, type, qty, cost, ref_type, ref_id, created_at) VALUES (?, ?, "out", ?, ?, "order", ?, NOW())');
                $stmt->execute(array($warehouseId, $item['product_id'], $item['qty'], $item['price'], $orderId));
            }

            if (!empty($data['services'])) {
                foreach ($data['services'] as $service) {
                    $stmt = $pdo->prepare('INSERT INTO order_services (order_id, service_id, qty, price, total) VALUES (?, ?, ?, ?, ?)');
                    $stmt->execute(array($orderId, $service['service_id'], $service['qty'], $service['price'], $service['total']));
                }
            }

            $pdo->commit();
            return $orderId;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }
}
