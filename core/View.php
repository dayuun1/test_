<?php
class View {
    private $templateDir = 'app/views/';
    private $cache;

    public function __construct() {
        $this->cache = new PageCache();
    }

    public function render($template, $data = []) {
        $cacheKey = $template . '_' . md5(serialize($data));

        if (http_response_code() == 200) {
            $cached = $this->cache->get($cacheKey, 200);
            if ($cached !== false) {
                return $cached;
            }
        }

        extract($data);

        ob_start();
        $templatePath = $this->templateDir . $template . '.php';

        if (file_exists($templatePath)) {
            include $templatePath;
        } else {
            throw new Exception("Template not found: $template");
        }

        $output = ob_get_clean();

        if (http_response_code() == 200) {
            $this->cache->set($cacheKey, $output, 200);
        }

        return $output;
    }
}