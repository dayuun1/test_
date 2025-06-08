<?php
class MangaRating extends Model {
    protected $table = 'manga_ratings';

    public function getUserRating($mangaId, $userId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE manga_id = ? AND user_id = ?");
        $stmt->execute([$mangaId, $userId]);
        return $stmt->fetch();
    }

    public function setRating($mangaId, $userId, $rating) {
        $existingRating = $this->getUserRating($mangaId, $userId);

        if ($existingRating) {
            // Оновлюємо існуючий рейтинг
            $stmt = $this->db->prepare("UPDATE {$this->table} SET rating = ?, updated_at = NOW() WHERE manga_id = ? AND user_id = ?");
            $result = $stmt->execute([$rating, $mangaId, $userId]);
        } else {
            // Створюємо новий рейтинг
            $stmt = $this->db->prepare("INSERT INTO {$this->table} (manga_id, user_id, rating) VALUES (?, ?, ?)");
            $result = $stmt->execute([$mangaId, $userId, $rating]);
        }

        if ($result) {
            $this->updateMangaRatingCache($mangaId);
        }

        return $result;
    }

    public function getMangaRatingStats($mangaId) {
        $stmt = $this->db->prepare("
            SELECT 
                AVG(rating) as average_rating,
                COUNT(*) as total_ratings,
                SUM(CASE WHEN rating = 5 THEN 1 ELSE 0 END) as rating_5,
                SUM(CASE WHEN rating = 4 THEN 1 ELSE 0 END) as rating_4,
                SUM(CASE WHEN rating = 3 THEN 1 ELSE 0 END) as rating_3,
                SUM(CASE WHEN rating = 2 THEN 1 ELSE 0 END) as rating_2,
                SUM(CASE WHEN rating = 1 THEN 1 ELSE 0 END) as rating_1
            FROM {$this->table} 
            WHERE manga_id = ?
        ");
        $stmt->execute([$mangaId]);
        return $stmt->fetch();
    }

    private function updateMangaRatingCache($mangaId) {
        $stats = $this->getMangaRatingStats($mangaId);

        $stmt = $this->db->prepare("
            UPDATE manga 
            SET average_rating = ?, ratings_count = ? 
            WHERE id = ?
        ");
        $stmt->execute([
            $stats['average_rating'] ?? 0,
            $stats['total_ratings'] ?? 0,
            $mangaId
        ]);
    }
}