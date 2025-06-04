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
    public function getGenres($mangaId) {
        $stmt = $this->db->prepare("
        SELECT g.* FROM genres g
        JOIN manga_genres mg ON g.id = mg.genre_id
        WHERE mg.manga_id = ?
    ");
        $stmt->execute([$mangaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addGenres($mangaId, $genreIds) {
        foreach ($genreIds as $genreId) {
            $stmt = $this->db->prepare("INSERT INTO manga_genres (manga_id, genre_id) VALUES (?, ?)");
            $stmt->execute([$mangaId, $genreId]);
        }
    }


    public function incrementViews($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET views = views + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}