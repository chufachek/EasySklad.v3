<?php
class ServiceModel
{
    public function allByCompany($companyId)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('SELECT * FROM services WHERE company_id = ? ORDER BY id DESC');
        $stmt->execute(array($companyId));
        return $stmt->fetchAll();
    }

    public function create($companyId, $data)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('INSERT INTO services (company_id, name, price, description, created_at) VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute(array($companyId, $data['name'], $data['price'], $data['description']));
        return $pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('UPDATE services SET name = ?, price = ?, description = ? WHERE id = ?');
        $stmt->execute(array($data['name'], $data['price'], $data['description'], $id));
        return true;
    }

    public function delete($id)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('DELETE FROM services WHERE id = ?');
        $stmt->execute(array($id));
        return true;
    }
}
