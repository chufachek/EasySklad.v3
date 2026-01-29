<?php
class WarehouseModel
{
    public function allByCompany($companyId)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('SELECT * FROM warehouses WHERE company_id = ? ORDER BY id DESC');
        $stmt->execute(array($companyId));
        return $stmt->fetchAll();
    }

    public function create($companyId, $data)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('INSERT INTO warehouses (company_id, name, address, created_at) VALUES (?, ?, ?, NOW())');
        $stmt->execute(array($companyId, $data['name'], $data['address']));
        return $pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('UPDATE warehouses SET name = ?, address = ? WHERE id = ?');
        $stmt->execute(array($data['name'], $data['address'], $id));
        return true;
    }

    public function delete($id)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('DELETE FROM warehouses WHERE id = ?');
        $stmt->execute(array($id));
        return true;
    }
}
