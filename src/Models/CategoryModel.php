<?php
class CategoryModel
{
    public function allByCompany($companyId)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('SELECT * FROM categories WHERE company_id = ? ORDER BY id DESC');
        $stmt->execute(array($companyId));
        return $stmt->fetchAll();
    }

    public function create($companyId, $data)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('INSERT INTO categories (company_id, name, created_at) VALUES (?, ?, NOW())');
        $stmt->execute(array($companyId, $data['name']));
        return $pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('UPDATE categories SET name = ? WHERE id = ?');
        $stmt->execute(array($data['name'], $id));
        return true;
    }

    public function delete($id)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute(array($id));
        return true;
    }
}
