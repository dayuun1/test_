<?php
class Genre extends Model {
    protected $table = 'genres';

    public function findBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public function getMangaByGenre($genreId, $limit = null) {
        $sql = "
            SELECT m.* 
            FROM manga m
            INNER JOIN manga_genres mg ON m.id = mg.manga_id
            WHERE mg.genre_id = ?
            ORDER BY m.created_at DESC
        ";

        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$genreId]);
        return $stmt->fetchAll();
    }

    public function getWithMangaCount() {
        $sql = "
            SELECT g.*, COUNT(mg.manga_id) as manga_count
            FROM {$this->table} g
            LEFT JOIN manga_genres mg ON g.id = mg.genre_id
            GROUP BY g.id
            ORDER BY g.name
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}