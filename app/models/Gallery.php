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
     * @param int $photoId
     * @return array|null
     */
    public function getPhotoById(int $photoId): ?array
    {
        $query = "SELECT g.id, g.photo, g.trip_id, g.comment, u.login AS author 
        FROM gallery g LEFT JOIN users u ON g.user_id = u.id
        WHERE g.id = ? AND g.deleted_at IS NULL";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $photoId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function edit(int $photoId, string $photoPath, string $comment): bool
    {
        $query = "UPDATE gallery SET photo = ?, comment = ? 
        WHERE id = ? AND deleted_at IS NULL";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssi", $photoPath, $comment, $photoId);
        return $stmt->execute();
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
        $stmt->bind_param("isis", $userId, $photo, $tripId, $comment);
        return $stmt->execute();
    }
}