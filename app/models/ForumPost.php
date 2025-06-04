<?php
class ForumPost extends Model {
    protected $table = 'forum_posts';

    public function getWithAuthor($conditions = []) {
        $sql = "
            SELECT 
                fp.*,
                u.username as author_name,
                u.role as author_role,
                u.avatar as author_avatar
            FROM {$this->table} fp
            LEFT JOIN users u ON fp.author_id = u.id
        ";

        $params = [];
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $field => $value) {
                $whereClause[] = "fp.$field = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }

        $sql .= " ORDER BY fp.created_at ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $postId = parent::create($data);

        // Оновлюємо лічильник відповідей в темі
        if (isset($data['topic_id'])) {
            $topicModel = new ForumTopic();
            $topicModel->updateRepliesCount($data['topic_id']);
        }

        return $postId;
    }
}