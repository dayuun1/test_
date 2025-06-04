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
}