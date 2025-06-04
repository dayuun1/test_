<?php
class Manga extends Model {
    protected $table = 'manga';

    public function findBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public function getWithGenres($id) {
        $sql = "
            SELECT m.*, GROUP_CONCAT(g.name) as genres
            FROM manga m
            LEFT JOIN manga_genres mg ON m.id = mg.manga_id
            LEFT JOIN genres g ON mg.genre_id = g.id
            WHERE m.id = ?
            GROUP BY m.id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function getPopular($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            ORDER BY views DESC, rating DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function incrementViews($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET views = views + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}