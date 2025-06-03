<?php
class Manga extends Model {
    protected $table = 'manga';

    public function findWithGenres($id) {
        $stmt = $this->db->prepare("
            SELECT m.*, GROUP_CONCAT(g.name) as genres 
            FROM manga m 
            LEFT JOIN manga_genres mg ON m.id = mg.manga_id 
            LEFT JOIN genres g ON mg.genre_id = g.id 
            WHERE m.id = ?
            GROUP BY m.id
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByGenre($genreId) {
        $stmt = $this->db->prepare("
            SELECT m.* FROM manga m 
            JOIN manga_genres mg ON m.id = mg.manga_id 
            WHERE mg.genre_id = ?
        ");
        $stmt->execute([$genreId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($query) {
        $stmt = $this->db->prepare("
            SELECT * FROM manga 
            WHERE title LIKE ? OR description LIKE ?
        ");
        $searchTerm = "%{$query}%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}