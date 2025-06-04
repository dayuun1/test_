<?php
class ChapterUpload {
    private $uploadDir = 'storage/uploads/chapters/';
    private $maxFileSize = 50 * 1024 * 1024; // 50MB

    public function upload($mangaId, $chapterNumber, $file) {
        // Валідація файлу
        if ($file['type'] !== 'application/pdf') {
            throw new Exception('Тільки PDF файли дозволені');
        }

        if ($file['size'] > $this->maxFileSize) {
            throw new Exception('Файл занадто великий (максимум 100MB)');
        }

        // Створення директорії
        $mangaDir = $this->uploadDir . $mangaId . '/';
        if (!is_dir($mangaDir)) {
            mkdir($mangaDir, 0755, true);
        }

        // Збереження файлу
        $filename = $chapterNumber . '.pdf';
        $filepath = $mangaDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filepath;
        }

        throw new Exception('Помилка завантаження файлу');
    }
}
?>