<?php
class PageCache {
    private $cacheDir;
    private $defaultTTL = 3600;

    public function __construct($cacheDir = 'storage/cache/') {
        $this->cacheDir = $cacheDir;
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
    }

    public function get($key, $statusCode = 200) {
        $filename = $this->getCacheFilename($key, $statusCode);

        if (file_exists($filename) && (time() - filemtime($filename)) < $this->defaultTTL) {
            return file_get_contents($filename);
        }

        return false;
    }

    public function set($key, $content, $statusCode = 200) {
        $filename = $this->getCacheFilename($key, $statusCode);

        // Кешуємо залежно від статус-коду
        $cacheConfig = [
            200 => 3600,    // Успішні сторінки - 1 година
            404 => 300,     // 404 помилки - 5 хвилин
            500 => 60       // Серверні помилки - 1 хвилина
        ];

        $ttl = isset($cacheConfig[$statusCode]) ? $cacheConfig[$statusCode] : $this->defaultTTL;

        if ($ttl > 0) {
            return file_put_contents($filename, $content);
        }

        return false;
    }

    private function getCacheFilename($key, $statusCode) {
        $hash = md5($key);
        return $this->cacheDir . "page_{$statusCode}_{$hash}.cache";
    }

    public function delete($key, $statusCode = 200) {
        $filename = $this->getCacheFilename($key, $statusCode);
        if (file_exists($filename)) {
            unlink($filename);
        }
    }

    public function clear() {
        $files = glob($this->cacheDir . '*.cache');
        foreach ($files as $file) {
            unlink($file);
        }
    }
}