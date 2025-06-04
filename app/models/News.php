<?php

class News extends Model
{
    protected $table = 'news';

    public function findBySlug($slug)
    {
        $sql = "
            SELECT n.*, u.username as author_name
            FROM {$this->table} n
            LEFT JOIN users u ON n.author_id = u.id
            WHERE n.slug = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        return $stmt->fetch();
    }

    public function getPublished($limit = 10, $offset = 0)
    {
        $sql = "
            SELECT n.*, u.username as author_name
            FROM {$this->table} n
            LEFT JOIN users u ON n.author_id = u.id
            WHERE n.is_published = 1
            ORDER BY n.created_at DESC
            LIMIT ? OFFSET ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll();
    }

    public function countPublished()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE is_published = 1");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getLatest($limit = 5)
    {
        return $this->getPublished($limit, 0);
    }

    public function incrementViews($id)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET views = views + 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAllWithAuthor()
    {
        $sql = "
            SELECT n.*, u.username as author_name
            FROM {$this->table} n
            LEFT JOIN users u ON n.author_id = u.id
            ORDER BY n.created_at DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}