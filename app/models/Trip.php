<?php

namespace app\models;

use Exception;

class Trip extends \app\core\AbstractDB
{
    // Створити запис
    public function create(array $trip): bool
    {
        $stmt = $this->db->prepare("INSERT INTO trips (name, user_id, difficulty_id, start_date, end_date, status_id, photo, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siississ", $trip['name'], $trip['user_id'], $trip['difficulty_id'], $trip['start_date'], $trip['end_date'], $trip['status_id'], $trip['photo'], $trip['description']);
        return $stmt->execute();
    }

    // Оновити запис
    public function update(array $trip): bool
    {
        $stmt = $this->db->prepare("UPDATE trips SET name = ?, user_id = ?, difficulty_id = ?, start_date = ?, end_date = ?, status_id = ?, photo = ?, description = ? WHERE id = ?");
        $stmt->bind_param("siississi", $trip['name'], $trip['user_id'], $trip['difficulty_id'], $trip['start_date'], $trip['end_date'], $trip['status_id'], $trip['photo'], $trip['description'], $trip['id']);
        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM trips WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function addLike(int $tripId, int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM likes_trip WHERE trip_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $tripId, $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result['count'] > 0) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO likes_trip (trip_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $tripId, $userId);
        return $stmt->execute();
    }

    public function deleteLike(int $tripId, int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM likes_trip WHERE trip_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $tripId, $userId);
        return $stmt->execute();
    }

    public function countLikes(int $tripId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM likes_trip WHERE trip_id = ?");
        $stmt->bind_param("i", $tripId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] ?? 0;
    }

    public function getDifficultyById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM difficulties WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public function getStatusById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM statuses WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public function getAllDifficulties(): ?array
    {
        $result = $this->db->query("SELECT * FROM difficulties");
        return $result->fetch_all(MYSQLI_ASSOC) ?: null;
    }

    public function getAllStatuses(): ?array
    {
        $result = $this->db->query("SELECT * FROM statuses");
        return $result->fetch_all(MYSQLI_ASSOC) ?: null;
    }

    public function createDifficult(array $difficulty): bool
    {
        $stmt = $this->db->prepare("INSERT INTO difficulties (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $difficulty['name'], $difficulty['description']);
        return $stmt->execute();
    }

    public function createStatus(array $status): bool
    {
        $stmt = $this->db->prepare("INSERT INTO statuses (name) VALUES (?)");
        $stmt->bind_param("s", $status['name']);
        return $stmt->execute();
    }

    function getInventoryByTrip(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT inventory_id FROM inventory_trip WHERE trip_id = ?");
        $stmt->bind_param(
            "i",
            $id
        );
        $stmt->execute();
        $result = $stmt->get_result();

        $inventoryList = $result->fetch_all(MYSQLI_ASSOC);

        return !empty($inventoryList) ? array_column($inventoryList, 'inventory_id') : null;
    }

    function createTripInventory(array $tripInv): bool
    {
        $stmt = $this->db->prepare("INSERT INTO inventory_trip (trip_id, inventory_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $tripInv['trip_id'], $tripInv['inventory_id']);

        return $stmt->execute();
    }

    function deleteTripInventory(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM inventory_trip WHERE inventory_id = ?");
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }
}
