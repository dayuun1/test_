<?php
class Character extends Model {
    protected $table = 'characters';

    public function getAllWithManga() {
        $sql = "
            SELECT c.*, m.title as manga_title, m.slug as manga_slug
            FROM {$this->table} c
            LEFT JOIN manga m ON c.manga_id = m.id
            ORDER BY c.created_at DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findByManga($mangaId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE manga_id = ? ORDER BY name");
        $stmt->execute([$mangaId]);
        return $stmt->fetchAll();
    }

    public function search($query, $limit = 20) {
        $sql = "
            SELECT c.*, m.title as manga_title, m.slug as manga_slug
            FROM {$this->table} c
            LEFT JOIN manga m ON c.manga_id = m.id
            WHERE c.name LIKE ? OR c.description LIKE ?
            ORDER BY c.name
            LIMIT ?
        ";

        $searchTerm = "%{$query}%";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $limit]);
        return $stmt->fetchAll();
    }

    public function getByMangaId($mangaId) {
        $stmt = $this->db->prepare("SELECT * FROM characters WHERE manga_id = ?");
        $stmt->execute([$mangaId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id)
    {
        $character = $this->find($id);

        if ($character && $character['image']) {
            $imagePath = 'public/uploads/characters/' . $character['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}