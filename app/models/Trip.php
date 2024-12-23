<?php

namespace app\models;

class Trip extends \app\core\AbstractDB
{
    /**
     * Returns all records from the "trips" table.
     * @return array|null List of trips or null if no records found.
     */
    public function getAll()
    {
        $query = "SELECT * FROM trips";
        $result = $this->db->query($query);
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();

        return $data ?: null;
    }

    /**
     * Creates a new trip record in the "trips" table.
     * @param array $trip An associative array containing trip data.
     * @return int|null The ID of the newly created trip, or null on failure.
     */
    public function create(array $trip): ?int
    {
        $stmt = $this->db->prepare("INSERT INTO trips (name, user_id, difficulty_id, start_date, end_date, status_id, photo, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siississ", $trip['name'], $trip['user_id'], $trip['difficulty_id'], $trip['start_date'], $trip['end_date'], $trip['status_id'], $trip['photo'], $trip['description']);

        if ($stmt->execute()) {
            return $this->db->insert_id;
        }

        return null;
    }

    /**
     * Updates an existing trip record in the "trips" table.
     * @param array $trip An associative array containing updated trip data, including the trip ID.
     * @return bool True if the update was successful, false otherwise.
     */
    public function update(array $trip): bool
    {
        $stmt = $this->db->prepare("UPDATE trips SET name = ?, difficulty_id = ?, start_date = ?, end_date = ?, status_id = ?, photo = ?, description = ? WHERE id = ?");
        $stmt->bind_param("sississi", $trip['name'], $trip['difficulty_id'], $trip['start_date'], $trip['end_date'], $trip['status_id'], $trip['photo'], $trip['description'], $trip['id']);
        return $stmt->execute();
    }

    /**
     * Deletes a trip record from the "trips" table by ID.
     * @param int $id The ID of the trip to delete.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM trips WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Checks if a user has liked a specific trip.
     * @param int $tripId The ID of the trip.
     * @param int $userId The ID of the user.
     * @return bool True if the user has not liked the trip, false otherwise.
     */
    public function checkLike(int $tripId, int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM likes_trip WHERE trip_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $tripId, $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result['count'] > 0) {
            return false;
        }
        return true;
    }

    /**
     * Adds a like to a trip from a specific user.
     * @param int $tripId The ID of the trip.
     * @param int $userId The ID of the user.
     * @return bool True if the like was added successfully, false otherwise.
     */
    public function addLike(int $tripId, int $userId): bool
    {
        $stmt = $this->db->prepare("INSERT INTO likes_trip (trip_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $tripId, $userId);
        return $stmt->execute();
    }

    /**
     * Removes a like from a trip for a specific user.
     * @param int $tripId The ID of the trip.
     * @param int $userId The ID of the user.
     * @return bool True if the like was removed successfully, false otherwise.
     */
    public function deleteLike(int $tripId, int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM likes_trip WHERE trip_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $tripId, $userId);
        return $stmt->execute();
    }

    /**
     * Counts the number of likes for a specific trip.
     * @param int $tripId The ID of the trip.
     * @return int The number of likes for the trip.
     */
    public function countLikes(int $tripId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM likes_trip WHERE trip_id = ?");
        $stmt->bind_param("i", $tripId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] ?? 0;
    }

    /**
     * Retrieves a difficulty record by its ID.
     * @param int $id The ID of the difficulty.
     * @return array|null The difficulty data or null if not found.
     */
    public function getDifficultyById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM difficulties WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    /**
     * Retrieves a status record by its ID.
     * @param int $id The ID of the status.
     * @return array|null The status data or null if not found.
     */
    public function getStatusById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM statuses WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    /**
     * Retrieves all difficulty records.
     * @return array|null List of all difficulties or null if no records found.
     */
    public function getAllDifficulties(): ?array
    {
        $result = $this->db->query("SELECT * FROM difficulties");
        return $result->fetch_all(MYSQLI_ASSOC) ?: null;
    }

    /**
     * Retrieves all status records.
     * @return array|null List of all statuses or null if no records found.
     */
    public function getAllStatuses(): ?array
    {
        $result = $this->db->query("SELECT * FROM statuses");
        return $result->fetch_all(MYSQLI_ASSOC) ?: null;
    }

    /**
     * Creates a new difficulty record.
     * @param array $difficulty An associative array containing difficulty data.
     * @return bool True if the difficulty was created successfully, false otherwise.
     */
    public function createDifficult(array $difficulty): bool
    {
        $stmt = $this->db->prepare("INSERT INTO difficulties (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $difficulty['name'], $difficulty['description']);
        return $stmt->execute();
    }

    /**
     * Creates a new status record.
     * @param array $status An associative array containing status data.
     * @return bool True if the status was created successfully, false otherwise.
     */
    public function createStatus(array $status): bool
    {
        $stmt = $this->db->prepare("INSERT INTO statuses (name) VALUES (?)");
        $stmt->bind_param("s", $status['name']);
        return $stmt->execute();
    }

    /**
     * Retrieves a list of inventory IDs associated with a specific trip.
     * @param int $id The ID of the trip.
     * @return array|null List of inventory IDs or null if none found.
     */
    function getInventoryByTrip(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT inventory_id FROM inventory_trip WHERE trip_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $inventoryList = $result->fetch_all(MYSQLI_ASSOC);

        return !empty($inventoryList) ? array_column($inventoryList, 'inventory_id') : null;
    }

    /**
     * Check if record of trip inventory exist
     * @param array $tripInv An associative array containing trip and inventory IDs.
     * @return bool
     */
    public function checkTripInventory(array $tripInv): bool
    {
        $stmt = $this->db->prepare("SELECT 1 FROM inventory_trip WHERE trip_id = ? AND inventory_id = ?");
        $stmt->bind_param("ii", $tripInv['trip_id'], $tripInv['inventory_id']);
        $stmt->execute();
        $stmt->store_result();

        return $stmt->num_rows > 0;
    }

    /**
     * Associates an inventory item with a trip.
     * @param array $tripInv An associative array containing trip and inventory IDs.
     * @return bool True if the association was created successfully, false otherwise.
     */
    function createTripInventory(array $tripInv): bool
    {
        if ($this->checkTripInventory($tripInv)) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO inventory_trip (trip_id, inventory_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $tripInv['trip_id'], $tripInv['inventory_id']);
        return $stmt->execute();
    }

    /**
     * Removes an inventory association from a trip.
     * @param int $id The ID of the inventory to remove.
     * @return bool True if the association was removed successfully, false otherwise.
     */
    function deleteTripInventory(int $tripId, int $invId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM inventory_trip WHERE inventory_id = ? AND trip_id = ?");
        $stmt->bind_param("ii", $invId, $tripId);

        return $stmt->execute();
    }
}
