<?php
class ExternalRating extends Model {
    protected $table = 'external_ratings';

    public function getByManga($mangaId) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE manga_id = ? ORDER BY source_name");
        $stmt->execute([$mangaId]);
        return $stmt->fetchAll();
    }

    public function updateRating($mangaId, $sourceName, $rating, $maxRating = 10, $votesCount = 0, $sourceUrl = null) {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (manga_id, source_name, rating, max_rating, votes_count, source_url)
            VALUES (?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                rating = VALUES(rating),
                max_rating = VALUES(max_rating),
                votes_count = VALUES(votes_count),
                source_url = VALUES(source_url),
                last_updated = NOW()
        ");

        return $stmt->execute([$mangaId, $sourceName, $rating, $maxRating, $votesCount, $sourceUrl]);
    }

    public function getAllSources() {
        return [
            'MyAnimeList' => 'https://myanimelist.net',
            'AniList' => 'https://anilist.co',
            'MangaUpdates' => 'https://www.mangaupdates.com',
            'Kitsu' => 'https://kitsu.io',
            'MangaDex' => 'https://mangadex.org'
        ];
    }
}