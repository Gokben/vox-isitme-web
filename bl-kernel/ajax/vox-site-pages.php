<?php defined('BLUDIT') or die('Bludit CMS.');
header('Content-Type: application/json; charset=utf-8');

checkRole(array('admin'));

$action = isset($_POST['action']) ? (string)$_POST['action'] : '';
$slug = isset($_POST['slug']) ? (string)$_POST['slug'] : '';
$allowedPages = array('hakkimizda', 'randevu', 'cihazlar', 'blog', 'iletisim');
if (!in_array($slug, $allowedPages, true) || !in_array($action, array('disable', 'restore'), true)) {
    ajaxResponse(1, 'Geçersiz sayfa işlemi.');
}

$databaseFile = PATH_DATABASES . 'vox-disabled-pages.json';
$disabledPages = array();
if (is_file($databaseFile)) {
    $decoded = json_decode((string)file_get_contents($databaseFile), true);
    if (is_array($decoded)) {
        $disabledPages = array_values(array_intersect($decoded, $allowedPages));
    }
}

if ($action === 'disable' && !in_array($slug, $disabledPages, true)) {
    $disabledPages[] = $slug;
} elseif ($action === 'restore') {
    $disabledPages = array_values(array_diff($disabledPages, array($slug)));
}

$json = json_encode($disabledPages, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
if ($json === false || file_put_contents($databaseFile, $json, LOCK_EX) === false) {
    ajaxResponse(1, 'Sayfa durumu kaydedilemedi.');
}

ajaxResponse(0, $action === 'disable' ? 'Sayfa yayından kaldırıldı.' : 'Sayfa geri yüklendi.');
