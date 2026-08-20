<?php defined('BLUDIT') or die('Bludit CMS.');
header('Content-Type: application/json; charset=utf-8');

checkRole(array('admin', 'editor'));

$action = isset($_POST['action']) ? (string)$_POST['action'] : '';
$pageKey = isset($_POST['pageKey']) ? trim((string)$_POST['pageKey']) : '';
$allowedTypes = array('heading', 'text', 'image', 'cta');

if (!preg_match('~^[a-zA-Z0-9/_-]{1,200}$~', $pageKey)) {
    ajaxResponse(1, 'Geçersiz sayfa.');
}

$databaseFile = PATH_DATABASES . 'vox-blocks.json';
$database = array();
if (is_file($databaseFile)) {
    $decoded = json_decode((string)file_get_contents($databaseFile), true);
    if (is_array($decoded)) {
        $database = $decoded;
    }
}
if (!isset($database[$pageKey]) || !is_array($database[$pageKey])) {
    $database[$pageKey] = array();
}

if ($action === 'add') {
    $type = isset($_POST['blockType']) ? (string)$_POST['blockType'] : '';
    if (!in_array($type, $allowedTypes, true)) {
        ajaxResponse(1, 'Geçersiz blok türü.');
    }

    $clean = static function ($name, $limit) {
        $value = isset($_POST[$name]) ? trim(strip_tags((string)$_POST[$name])) : '';
        return mb_substr($value, 0, $limit);
    };
    $cleanUrl = static function ($name) use ($clean) {
        $value = $clean($name, 1000);
        if ($value === '') {
            return '';
        }
        if (preg_match('~^(?:https?://|/|#|mailto:|tel:)~i', $value)) {
            return $value;
        }
        return '';
    };

    $block = array(
        'id' => bin2hex(random_bytes(8)),
        'type' => $type,
        'title' => $clean('title', 160),
        'text' => $clean('text', 3000),
        'imageUrl' => $cleanUrl('imageUrl'),
        'buttonLabel' => $clean('buttonLabel', 80),
        'buttonUrl' => $cleanUrl('buttonUrl'),
    );

    if (($type === 'heading' && $block['title'] === '') || ($type === 'text' && $block['text'] === '') || ($type === 'image' && $block['imageUrl'] === '')) {
        ajaxResponse(1, 'Lütfen blok için gerekli alanları doldurun.');
    }
    $database[$pageKey][] = $block;
} elseif ($action === 'delete') {
    $blockId = isset($_POST['blockId']) ? (string)$_POST['blockId'] : '';
    $database[$pageKey] = array_values(array_filter($database[$pageKey], static function ($block) use ($blockId) {
        return !is_array($block) || !isset($block['id']) || !hash_equals((string)$block['id'], $blockId);
    }));
} else {
    ajaxResponse(1, 'Geçersiz işlem.');
}

$json = json_encode($database, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
if ($json === false || file_put_contents($databaseFile, $json, LOCK_EX) === false) {
    ajaxResponse(1, 'Bloklar kaydedilemedi.');
}

ajaxResponse(0, $action === 'delete' ? 'Blok silindi.' : 'Blok eklendi.');
