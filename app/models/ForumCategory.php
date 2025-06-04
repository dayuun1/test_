
<?php
// ForumCategory.php
class ForumCategory extends Model {
    protected $table = 'forum_categories';

    public function getWithStats() {
        $sql = "
            SELECT 
                fc.*,
                COUNT(DISTINCT ft.id) as topics_count,
                COUNT(fp.id) as posts_count,
                MAX(fp.created_at) as last_post_date
            FROM {$this->table} fc
            LEFT JOIN forum_topics ft ON fc.id = ft.category_id
            LEFT JOIN forum_posts fp ON ft.id = fp.topic_id
            GROUP BY fc.id
            ORDER BY fc.name
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}