<?php
class View {
    private $templateDir = 'app/views/';

    public function __construct() {
    }

    public function render($template, $data = []) {
        extract($data);

        ob_start();
        $templatePath = $this->templateDir . $template . '.php';

        if (!file_exists($templatePath)) {
            throw new Exception("Template not found: $template");
        }

        include $templatePath;

        return ob_get_clean();
    }
}