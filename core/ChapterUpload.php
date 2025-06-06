<?php
class ChapterUpload {
    private $uploadDir = 'storage/uploads/chapters/';
    private $maxFileSize = 100 * 1024 * 1024;

    public function upload($mangaId, $chapterNumber, $file) {
        if ($file['type'] !== 'application/pdf') {
            throw new Exception('Тільки PDF файли дозволені');
        }

        if ($file['size'] > $this->maxFileSize) {
            throw new Exception('Файл занадто великий (максимум 100MB)');
        }

        $mangaDir = $this->uploadDir . $mangaId . '/';
        if (!is_dir($mangaDir)) {
            mkdir($mangaDir, 0755, true);
        }

        $filename = $chapterNumber . '.pdf';
        $filepath = $mangaDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filepath;
        }

        throw new Exception('Помилка завантаження файлу');
    }
}
?>