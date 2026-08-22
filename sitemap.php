<?php
declare(strict_types=1);

define('BLUDIT', true);
define('DS', DIRECTORY_SEPARATOR);
define('PATH_ROOT', __DIR__ . DS);
define('PATH_BOOT', PATH_ROOT . 'bl-kernel' . DS . 'boot' . DS);
require PATH_BOOT . 'init.php';

header('Content-Type: application/xml; charset=UTF-8');

$baseUrl = rtrim((string)$site->url(), '/');
$urls = [$baseUrl . '/'];
$staticPages = $pages->getList(1, -1, true, true, true, false, false);
foreach ($staticPages as $pageKey) {
    try {
        $sitemapPage = new Page($pageKey);
        if (!$sitemapPage->noindex()) {
            $urls[] = $sitemapPage->permalink(true);
        }
    } catch (Throwable $exception) {
        // Hatalı tek bir içerik, site haritasını engellemesin.
    }
}

foreach (['cihazlar', 'blog', 'iletisim'] as $route) {
    $urls[] = $baseUrl . '/' . $route;
}

$blogFile = PATH_DATABASES . 'vox-blog.json';
if (is_file($blogFile)) {
    $blogPosts = json_decode((string)file_get_contents($blogFile), true);
    if (is_array($blogPosts)) {
        foreach ($blogPosts as $blogPost) {
            $slug = is_array($blogPost) ? (string)($blogPost['slug'] ?? '') : '';
            if (preg_match('/^[a-z0-9-]{3,180}$/', $slug)) {
                $urls[] = $baseUrl . '/blog?yazi=' . rawurlencode($slug);
            }
        }
    }
}

$urls = array_values(array_unique($urls));
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as $url) {
    echo "  <url><loc>" . htmlspecialchars($url, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc></url>\n";
}
echo "</urlset>\n";
