<?php

namespace app\models;

use app\core\AbstractDB;

class Gallery extends AbstractDB
{
    /**
     * get photo by ID
     * @param int $id
     * @return array|null
     */
    public function getPhotoById(int $id): array | null
    {
        $query = "SELECT g.id, g.photo, g.comment, u.login AS author
              FROM gallery g
              JOIN users u ON g.user_id = u.id
              WHERE g.id = ? AND g.deleted_at IS NULL";
        $result = null;
        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($id, $photo, $comment, $author);
            if ($stmt->fetch()) {
                $result = [
                    'id' => $id,
                    'photo' => $photo,
                    'comment' => $comment,
                    'author' => $author,
                ];
            }

            $stmt->close();
        }
        return $result;
    }

    /**
     * update photo in DB
     * @param int $id
     * @param string $photoPath
     * @param string $description
     * @return bool
     */
    public function updatePhoto(int $id, string $photoPath, string $description): bool
    {
        $query = "UPDATE gallery SET photo = ?, comment = ? WHERE id = ?";
        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param('ssi', $photoPath, $description, $id);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
        return false;
    }

    /**
     * get photo path by its id
     * @param int $id
     * @return string|null
     */
    public function getPhotoPathById(int $id): ?string
    {
        $query = "SELECT photo FROM gallery WHERE id = ? AND deleted_at IS NULL";
        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->bind_result($photoPath);
            if ($stmt->fetch()) {
                return $photoPath;
            }
            $stmt->close();
        }
        return null;
    }
}