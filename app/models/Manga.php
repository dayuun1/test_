<?php
class Manga extends Model {
    protected $table = 'manga';

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY title ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    public function findAllWithGenres() {
        $stmt = $this->db->query("
        SELECT m.*, 
               GROUP_CONCAT(g.name SEPARATOR ', ') AS genres
        FROM manga m
        LEFT JOIN manga_genres mg ON m.id = mg.manga_id
        LEFT JOIN genres g ON g.id = mg.genre_id
        GROUP BY m.id
        ORDER BY m.created_at DESC
    ");
        return $stmt->fetchAll();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM manga WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function searchByTitle($query) {
        $sql = "SELECT * FROM manga WHERE title LIKE :query ORDER BY views DESC LIMIT 50";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['query' => '%' . $query . '%']);
        return $stmt->fetchAll();
    }

    public function updateGenres($mangaId, $genreIds) {
        $stmt = $this->db->prepare("DELETE FROM manga_genres WHERE manga_id = ?");
        $stmt->execute([$mangaId]);

        $stmt = $this->db->prepare("INSERT INTO manga_genres (manga_id, genre_id) VALUES (?, ?)");
        foreach ($genreIds as $genreId) {
            $stmt->execute([$mangaId, $genreId]);
        }
    }

}