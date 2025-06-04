<?php
class ForumTopic extends Model {
    protected $table = 'forum_topics';

    public function getWithAuthor($conditions = [], $limit = null) {
        $sql = "
            SELECT 
                ft.*,
                u.username as author_name,
                (SELECT COUNT(*) FROM forum_posts WHERE topic_id = ft.id) as replies_count,
                (SELECT MAX(created_at) FROM forum_posts WHERE topic_id = ft.id) as last_reply_date
            FROM {$this->table} ft
            LEFT JOIN users u ON ft.author_id = u.id
        ";

        $params = [];
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $field => $value) {
                $whereClause[] = "ft.$field = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }

        $sql .= " ORDER BY ft.is_pinned DESC, ft.updated_at DESC";

        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function incrementViews($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET views = views + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateRepliesCount($topicId) {
        $sql = "
            UPDATE {$this->table} 
            SET replies_count = (
                SELECT COUNT(*) - 1 FROM forum_posts WHERE topic_id = ?
            )
            WHERE id = ?
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$topicId, $topicId]);
    }
}