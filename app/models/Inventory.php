<?php

namespace app\models;

class Inventory extends \app\core\AbstractDB
{
    //Create card with inventory
    public function create(array $inventory): bool
    {
        $stmt = $this->db->prepare("INSERT INTO inventory (name, description, photo) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $inventory['name'], $inventory['description'], $inventory['photo']);
        return $stmt->execute();
    }
    //Update inventory card
    public function update(array $inventory): bool
    {
        $stmt = $this->db->prepare("UPDATE inventory SET name = ?, description = ?, photo = ? WHERE id = ?");
        $stmt->bind_param("sssi", $inventory['name'], $inventory['description'], $inventory['photo'], $inventory['id']);
        return $stmt->execute();
    }
    //Delete inventory card
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM inventory WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    //Get all inventory
    public function getAllInventory(): ?array
    {
        $result = $this->db->query("SELECT * FROM inventory");
        return $result->fetch_all(MYSQLI_ASSOC) ?: null;
    }
}
