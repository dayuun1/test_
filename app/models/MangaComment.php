<?php
class MangaComment extends Model {
    protected $table = 'manga_comments';

    public function getByManga($mangaId, $limit = 20, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT 
                mc.*,
                u.username as author_name,
                u.role as author_role,
                u.avatar as author_avatar,
                (SELECT COUNT(*) FROM manga_comments WHERE parent_id = mc.id AND is_deleted = FALSE) as replies_count
            FROM {$this->table} mc
            LEFT JOIN users u ON mc.user_id = u.id
            WHERE mc.manga_id = ? AND mc.parent_id IS NULL AND mc.is_deleted = FALSE
            ORDER BY mc.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$mangaId, $limit, $offset]);
        return $stmt->fetchAll();
    }

    public function getReplies($commentId) {
        $stmt = $this->db->prepare("
            SELECT 
                mc.*,
                u.username as author_name,
                u.role as author_role,
                u.avatar as author_avatar
            FROM {$this->table} mc
            LEFT JOIN users u ON mc.user_id = u.id
            WHERE mc.parent_id = ? AND mc.is_deleted = FALSE
            ORDER BY mc.created_at ASC
        ");
        $stmt->execute([$commentId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $commentId = parent::create($data);

        if ($commentId) {
            $this->updateMangaCommentsCount($data['manga_id']);
        }

        return $commentId;
    }

    public function deleteComment($commentId, $userId) {
        // Перевіряємо чи користувач може видалити коментар
        $comment = $this->find($commentId);
        if (!$comment || ($comment['user_id'] != $userId && !Auth::hasRole('admin'))) {
            return false;
        }

        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_deleted = TRUE WHERE id = ?");
        $result = $stmt->execute([$commentId]);

        if ($result) {
            $this->updateMangaCommentsCount($comment['manga_id']);
        }

        return $result;
    }

    public function countByManga($mangaId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE manga_id = ? AND is_deleted = FALSE");
        $stmt->execute([$mangaId]);
        return $stmt->fetchColumn();
    }

    private function updateMangaCommentsCount($mangaId) {
        $count = $this->countByManga($mangaId);

        $stmt = $this->db->prepare("UPDATE manga SET comments_count = ? WHERE id = ?");
        $stmt->execute([$count, $mangaId]);
    }
}