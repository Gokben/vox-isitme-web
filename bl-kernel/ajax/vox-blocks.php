<?php defined('BLUDIT') or die('Bludit CMS.');
header('Content-Type: application/json; charset=utf-8');

checkRole(array('admin', 'editor'));

$action = isset($_POST['action']) ? (string)$_POST['action'] : '';
$pageKey = isset($_POST['pageKey']) ? trim((string)$_POST['pageKey']) : '';
$allowedTypes = array('heading', 'text', 'image', 'cta');

if (!preg_match('~^[a-zA-Z0-9/_-]{1,200}$~', $pageKey)) {
    ajaxResponse(1, 'Geçersiz sayfa.');
}

if ($action === 'save-home') {
    $allowedHomeFields = array(
        'hero_eyebrow', 'hero_title', 'hero_description', 'services_kicker', 'services_title',
        'service_1_title', 'service_1_text', 'service_2_title', 'service_2_text',
        'service_3_title', 'service_3_text', 'service_4_title', 'service_4_text',
        'impact_kicker', 'impact_title', 'about_kicker', 'about_title', 'about_text',
        'why_kicker', 'why_title', 'why_1_title', 'why_1_text', 'why_2_title',
        'why_2_text', 'why_3_title', 'why_3_text', 'hours_kicker', 'hours_title',
        'hours_text', 'hours_card_title', 'hours_weekday', 'hours_saturday',
        'hours_sunday', 'cta_title', 'cta_text', 'hero_image', 'service_1_image',
        'service_2_image', 'service_3_image', 'service_4_image', 'about_image'
    );
    $homeImageFields = array('hero_image', 'service_1_image', 'service_2_image', 'service_3_image', 'service_4_image', 'about_image');
    $postedFields = isset($_POST['fields']) ? json_decode((string)$_POST['fields'], true) : null;
    if ($pageKey !== 'home' || !is_array($postedFields)) {
        ajaxResponse(1, 'Geçersiz ana sayfa verisi.');
    }
    $homeContent = array();
    foreach ($allowedHomeFields as $field) {
        if (isset($postedFields[$field])) {
            $value = trim(strip_tags((string)$postedFields[$field]));
            if (in_array($field, $homeImageFields, true) && !preg_match('~^(?:https?://|/)~i', $value)) {
                $value = '';
            }
            $homeContent[$field] = mb_substr($value, 0, 3000);
        }
    }
    $homeJson = json_encode($homeContent, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    if ($homeJson === false || file_put_contents(PATH_DATABASES . 'vox-home.json', $homeJson, LOCK_EX) === false) {
        ajaxResponse(1, 'Ana sayfa kaydedilemedi.');
    }
    ajaxResponse(0, 'Ana sayfa güncellendi.');
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

if ($action === 'add' || $action === 'update') {
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

    $blockId = $action === 'update' && isset($_POST['blockId']) ? (string)$_POST['blockId'] : bin2hex(random_bytes(8));
    if (!preg_match('~^[a-f0-9]{16}$~', $blockId)) {
        ajaxResponse(1, 'Geçersiz blok.');
    }
    $block = array(
        'id' => $blockId,
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
    if ($action === 'update') {
        $updated = false;
        foreach ($database[$pageKey] as $index => $existingBlock) {
            if (is_array($existingBlock) && isset($existingBlock['id']) && hash_equals((string)$existingBlock['id'], $blockId)) {
                $database[$pageKey][$index] = $block;
                $updated = true;
                break;
            }
        }
        if (!$updated) {
            ajaxResponse(1, 'Düzenlenecek blok bulunamadı.');
        }
    } else {
        $database[$pageKey][] = $block;
    }
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

ajaxResponse(0, $action === 'delete' ? 'Blok silindi.' : ($action === 'update' ? 'Blok güncellendi.' : 'Blok eklendi.'));
