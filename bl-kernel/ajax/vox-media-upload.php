<?php defined('BLUDIT') or die('Bludit CMS.');
header('Content-Type: application/json; charset=utf-8');

checkRole(array('admin', 'editor'));

if (!isset($_FILES['images']) || !is_array($_FILES['images']['name'])) {
    ajaxResponse(1, 'Lütfen en az bir görsel seçin.');
}

$directory = PATH_UPLOADS . 'vox-media' . DS;
if (!Filesystem::directoryExists($directory) && !Filesystem::mkdir($directory, true)) {
    ajaxResponse(1, 'Medya klasörü oluşturulamadı.');
}

$mimeExtensions = array(
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
    'image/gif' => 'gif',
);
$uploaded = array();
$count = min(count($_FILES['images']['name']), 20);

for ($index = 0; $index < $count; $index++) {
    $error = (int)$_FILES['images']['error'][$index];
    $size = (int)$_FILES['images']['size'][$index];
    $temporary = (string)$_FILES['images']['tmp_name'][$index];
    if ($error !== UPLOAD_ERR_OK || $size < 1 || $size > 8 * 1024 * 1024) {
        continue;
    }
    $imageInfo = @getimagesize($temporary);
    $mime = is_array($imageInfo) && isset($imageInfo['mime']) ? $imageInfo['mime'] : '';
    if (!isset($mimeExtensions[$mime])) {
        continue;
    }
    $originalBase = pathinfo((string)$_FILES['images']['name'][$index], PATHINFO_FILENAME);
    $safeBase = Text::lowercase($originalBase);
    $safeBase = preg_replace('~[^a-z0-9]+~', '-', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $safeBase) ?: $safeBase);
    $safeBase = trim((string)$safeBase, '-');
    if ($safeBase === '') {
        $safeBase = 'gorsel';
    }
    $filename = substr($safeBase, 0, 70) . '-' . bin2hex(random_bytes(4)) . '.' . $mimeExtensions[$mime];
    if (move_uploaded_file($temporary, $directory . $filename)) {
        $uploaded[] = array(
            'filename' => $filename,
            'url' => DOMAIN_UPLOADS . 'vox-media/' . rawurlencode($filename),
        );
    }
}

if ($uploaded === array()) {
    ajaxResponse(1, 'Görseller yüklenemedi. Dosya türünü ve 8 MB sınırını kontrol edin.');
}

ajaxResponse(0, count($uploaded) . ' görsel yüklendi.', array('files' => $uploaded));
