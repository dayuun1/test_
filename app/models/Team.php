<?php
class Team extends Model {
    protected $table = 'teams';

    public function getAllWithMembers() {
        $stmt = $this->db->query("
            SELECT t.*, 
                (SELECT COUNT(*) FROM team_user WHERE team_id = t.id) AS member_count
            FROM teams t
            ORDER BY created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function getMembers($teamId) {
        $stmt = $this->db->prepare("
            SELECT u.* FROM users u
            JOIN team_user tu ON u.id = tu.user_id
            WHERE tu.team_id = ?
        ");
        $stmt->execute([$teamId]);
        return $stmt->fetchAll();
    }

    public function addMember($teamId, $userId) {
        $stmt = $this->db->prepare("
            INSERT INTO team_user (team_id, user_id) VALUES (?, ?)
        ");
        $stmt->execute([$teamId, $userId]);
    }

    public function assignManga($teamId, $mangaId) {
        $stmt = $this->db->prepare("
            INSERT INTO team_manga_access (team_id, manga_id) VALUES (?, ?)
        ");
        $stmt->execute([$teamId, $mangaId]);
    }

    public function getAccessibleManga($teamId) {
        $stmt = $this->db->prepare("
            SELECT m.* FROM manga m
            JOIN team_manga_access tma ON m.id = tma.manga_id
            WHERE tma.team_id = ?
        ");
        $stmt->execute([$teamId]);
        return $stmt->fetchAll();
    }

    public function addAccessibleManga($teamId, $mangaId) {
        $stmt = $this->db->prepare("INSERT INTO team_manga_access (team_id, manga_id) VALUES (?, ?)");
        $stmt->execute([$teamId, $mangaId]);
    }
}