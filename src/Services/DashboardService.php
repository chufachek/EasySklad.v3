<?php
class DashboardService
{
    public function stats($companyId, $warehouseId, $range)
    {
        $pdo = Db::connection();
        $days = $range === '30d' ? 30 : 7;
        $startDate = date('Y-m-d', strtotime('-' . ($days - 1) . ' days'));

        $stmt = $pdo->prepare('SELECT SUM(total) as revenue_total, COUNT(*) as orders_count FROM orders WHERE warehouse_id = ? AND status = "paid" AND created_at >= ?');
        $stmt->execute(array($warehouseId, $startDate));
        $summary = $stmt->fetch();

        $stmt = $pdo->prepare('SELECT DATE(created_at) as date, SUM(total) as value FROM orders WHERE warehouse_id = ? AND status = "paid" AND created_at >= ? GROUP BY DATE(created_at) ORDER BY DATE(created_at) ASC');
        $stmt->execute(array($warehouseId, $startDate));
        $series = $stmt->fetchAll();

        $stmt = $pdo->prepare('SELECT status, COUNT(*) as count FROM orders WHERE warehouse_id = ? AND created_at >= ? GROUP BY status');
        $stmt->execute(array($warehouseId, $startDate));
        $statusRows = $stmt->fetchAll();
        $paidCount = 0;
        $draftCount = 0;
        foreach ($statusRows as $row) {
            if ($row['status'] === 'paid') {
                $paidCount = (int)$row['count'];
            } elseif ($row['status'] === 'draft') {
                $draftCount = (int)$row['count'];
            }
        }

        $stmt = $pdo->prepare('SELECT COUNT(*) as stock_total_sku, SUM(CASE WHEN ps.qty <= p.min_stock THEN 1 ELSE 0 END) as stock_low_count, SUM(CASE WHEN ps.qty <= 0 THEN 1 ELSE 0 END) as stock_out_count FROM products p LEFT JOIN product_stocks ps ON ps.product_id = p.id AND ps.warehouse_id = p.warehouse_id WHERE p.warehouse_id = ?');
        $stmt->execute(array($warehouseId));
        $stocks = $stmt->fetch();

        $stmt = $pdo->prepare('SELECT id, customer_name, total, status, created_at FROM orders WHERE warehouse_id = ? ORDER BY id DESC LIMIT 5');
        $stmt->execute(array($warehouseId));
        $lastOrders = $stmt->fetchAll();

        $stmt = $pdo->prepare('SELECT type, qty, cost, created_at FROM stock_movements WHERE warehouse_id = ? ORDER BY id DESC LIMIT 5');
        $stmt->execute(array($warehouseId));
        $lastOps = $stmt->fetchAll();

        $stmt = $pdo->prepare('SELECT p.name, SUM(oi.qty) as qty FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE p.warehouse_id = ? GROUP BY oi.product_id ORDER BY qty DESC LIMIT 5');
        $stmt->execute(array($warehouseId));
        $topProducts = $stmt->fetchAll();

        $stmt = $pdo->prepare('SELECT p.name, ps.qty, p.min_stock FROM products p JOIN product_stocks ps ON ps.product_id = p.id AND ps.warehouse_id = p.warehouse_id WHERE p.warehouse_id = ? AND ps.qty <= p.min_stock ORDER BY ps.qty ASC LIMIT 5');
        $stmt->execute(array($warehouseId));
        $lowStock = $stmt->fetchAll();

        $pieSeries = array(
            array('label' => 'Оплачено', 'value' => $paidCount),
            array('label' => 'Черновики', 'value' => $draftCount)
        );

        return array(
            'revenue_total' => $summary && $summary['revenue_total'] ? (float)$summary['revenue_total'] : 0,
            'revenue_series' => $series,
            'orders_count' => $summary ? (int)$summary['orders_count'] : 0,
            'avg_check' => $summary && $summary['orders_count'] ? round($summary['revenue_total'] / $summary['orders_count'], 2) : 0,
            'paid_count' => $paidCount,
            'draft_count' => $draftCount,
            'stock_total_sku' => $stocks ? (int)$stocks['stock_total_sku'] : 0,
            'stock_low_count' => $stocks ? (int)$stocks['stock_low_count'] : 0,
            'stock_out_count' => $stocks ? (int)$stocks['stock_out_count'] : 0,
            'last_orders' => $lastOrders,
            'last_ops' => $lastOps,
            'pie_series' => $pieSeries,
            'top_products' => $topProducts,
            'low_stock_products' => $lowStock
        );
    }
}
