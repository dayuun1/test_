<?php
class Chapter extends Model {
    protected $table = 'chapters';

    public function findByMangaAndNumber($mangaId, $chapterNumber) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE manga_id = ? AND chapter_number = ?");
        $stmt->execute([$mangaId, $chapterNumber]);
        return $stmt->fetch();
    }

    public function incrementViews($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET views = views + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getByMangaPaginated($mangaId, $limit, $offset) {
        $stmt = $this->db->prepare("SELECT * FROM chapters WHERE manga_id = :mangaId ORDER BY chapter_number DESC LIMIT :limit OFFSET :offset");

        $stmt->bindValue(':mangaId', $mangaId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countByManga($mangaId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM chapters WHERE manga_id = :mangaId");
        $stmt->bindValue(':mangaId', $mangaId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }

}