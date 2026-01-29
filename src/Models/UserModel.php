<?php
class UserModel
{
    public function findByEmail($email)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute(array($email));
        return $stmt->fetch();
    }

    public function findById($id)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute(array($id));
        return $stmt->fetch();
    }

    public function create($data)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('INSERT INTO users (email, password_hash, first_name, last_name, username, balance, tariff, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute(array(
            $data['email'],
            $data['password_hash'],
            $data['first_name'],
            $data['last_name'],
            $data['username'],
            $data['balance'],
            $data['tariff']
        ));
        return $pdo->lastInsertId();
    }

    public function update($id, $data)
    {
        $pdo = Db::connection();
        $stmt = $pdo->prepare('UPDATE users SET email = ?, first_name = ?, last_name = ?, username = ? WHERE id = ?');
        $stmt->execute(array($data['email'], $data['first_name'], $data['last_name'], $data['username'], $id));
        return true;
    }
}
