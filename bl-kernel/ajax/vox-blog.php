<?php defined('BLUDIT') or die('Bludit CMS.');
header('Content-Type: application/json; charset=utf-8');

checkRole(array('admin', 'editor'));

$action = isset($_POST['action']) ? (string)$_POST['action'] : '';
$slug = isset($_POST['slug']) ? trim((string)$_POST['slug']) : '';
if (!preg_match('~^[a-z0-9-]{3,180}$~', $slug)) {
    ajaxResponse(1, 'Geçersiz blog yazısı.');
}

$databaseFile = PATH_DATABASES . 'vox-blog.json';
$blogPosts = is_file($databaseFile) ? json_decode((string)file_get_contents($databaseFile), true) : null;
if (!is_array($blogPosts)) {
    ajaxResponse(1, 'Blog veritabanı bulunamadı. Blog sayfasını yenileyip tekrar deneyin.');
}

$postIndex = null;
foreach ($blogPosts as $index => $post) {
    if (is_array($post) && isset($post['slug']) && hash_equals((string)$post['slug'], $slug)) {
        $postIndex = $index;
        break;
    }
}
if ($postIndex === null) {
    ajaxResponse(1, 'Blog yazısı bulunamadı.');
}

if ($action === 'delete') {
    array_splice($blogPosts, $postIndex, 1);
} elseif ($action === 'update') {
    $clean = static function ($name, $limit) {
        $value = isset($_POST[$name]) ? trim(strip_tags((string)$_POST[$name])) : '';
        return mb_substr($value, 0, $limit);
    };
    $title = $clean('title', 180);
    $date = $clean('date', 60);
    $excerpt = $clean('excerpt', 600);
    $alt = $clean('alt', 180);
    $image = $clean('image', 1000);
    $contentText = $clean('content', 12000);

    if ($title === '' || $date === '' || $excerpt === '' || $contentText === '' || !preg_match('~^(?:https?://|/)~i', $image)) {
        ajaxResponse(1, 'Lütfen gerekli alanları geçerli bilgilerle doldurun.');
    }

    $paragraphs = preg_split('~(?:\r?\n){2,}~', $contentText);
    $contentHtml = '';
    foreach ($paragraphs as $paragraph) {
        $paragraph = trim($paragraph);
        if ($paragraph !== '') {
            $contentHtml .= '<p>' . nl2br(htmlspecialchars($paragraph, ENT_QUOTES, 'UTF-8')) . '</p>';
        }
    }

    $blogPosts[$postIndex] = array(
        'slug' => $slug,
        'date' => $date,
        'title' => $title,
        'image' => $image,
        'alt' => $alt,
        'excerpt' => $excerpt,
        'content' => $contentHtml,
    );
} else {
    ajaxResponse(1, 'Geçersiz işlem.');
}

$json = json_encode($blogPosts, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
if ($json === false || file_put_contents($databaseFile, $json, LOCK_EX) === false) {
    ajaxResponse(1, 'Blog değişiklikleri kaydedilemedi.');
}

ajaxResponse(0, $action === 'delete' ? 'Blog yazısı silindi.' : 'Blog yazısı güncellendi.');
