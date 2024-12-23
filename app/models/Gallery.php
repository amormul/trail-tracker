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
     * get photo info
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

    /**
     * change photo info
     * @param int $photoId
     * @param string $photoPath
     * @param string $comment
     * @return bool
     */
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

    /**
     * checks if a user has liked a specific photo
     * @param int $photoId
     * @param int $userId
     * @return bool
     */
    public function checkLike(int $photoId, int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM likes_photo WHERE photo_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $photoId, $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if ($result['count'] > 0) {
            return false;
        }
        return true;
    }

    /**
     * adds a like to a trip from a specific user
     * @param int $photoId
     * @param int $userId
     * @return bool
     */
    public function addLike(int $photoId, int $userId): bool
    {
        $stmt = $this->db->prepare("INSERT INTO likes_photo (photo_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $photoId, $userId);
        return $stmt->execute();
    }

    /**
     * removes a like from a trip for a specific user
     * @param int $photoId
     * @param int $userId
     * @return bool
     */
    public function deleteLike(int $photoId, int $userId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM likes_photo WHERE photo_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $photoId, $userId);
        return $stmt->execute();
    }

    /**
     * counts the number of likes for a specific photo
     * @param int $photoId
     * @return int
     */
    public function countLikes(int $photoId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM likes_photo WHERE photo_id = ?");
        $stmt->bind_param("i", $photoId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] ?? 0;
    }
}