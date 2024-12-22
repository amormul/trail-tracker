<?php

namespace app\models;

use app\core\AbstractDB;

class Gallery extends AbstractDB
{
    /**
     * get photos by trip_id
     * @param int $tripId
     * @return array
     */
    public function getPhotosByTripId(int $tripId): array
    {
        $query = "SELECT id, photo, comment FROM gallery WHERE trip_id = ? AND deleted_at IS NULL";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $tripId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * add new photo
     * @param int $userId
     * @param string $photo
     * @param int $tripId
     * @param string|null $comment
     * @return bool
     */
    public function create(int $userId, string $photo, int $tripId, ?string $comment = null): bool
    {
        $query = "INSERT INTO gallery (user_id, photo, trip_id, comment) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        if ($stmt) {
            $stmt->bind_param("isis", $userId, $photo, $tripId, $comment);
            return $stmt->execute();
        }
        return false;
    }
}