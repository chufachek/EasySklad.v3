<?php
class CompanyModel
{
    public function allByOwner($userId)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('SELECT * FROM companies WHERE owner_user_id = ? ORDER BY id DESC');
        $stmt->execute(array($userId));
        return $stmt->fetchAll();
    }

    public function create($userId, $data)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('INSERT INTO companies (owner_user_id, name, inn, address, created_at) VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute(array($userId, $data['name'], $data['inn'], $data['address']));
        return $pdo->lastInsertId();
    }

    public function update($id, $userId, $data)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('UPDATE companies SET name = ?, inn = ?, address = ? WHERE id = ? AND owner_user_id = ?');
        $stmt->execute(array($data['name'], $data['inn'], $data['address'], $id, $userId));
        return true;
    }
}
