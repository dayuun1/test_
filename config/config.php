<?php
return [
    'app_name' => 'MangaSite',
    'app_url' => 'http://localhost',
    'upload_max_size' => 50 * 1024 * 1024, // 50MB
    'cache_ttl' => 3600, // 1 hour
    'pagination_limit' => 12,
    'allowed_file_types' => ['pdf'],
    'allowed_image_types' => ['jpg', 'jpeg', 'png', 'webp']
];
?>