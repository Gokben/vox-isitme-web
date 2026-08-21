<?php defined('BLUDIT') or die('Bludit CMS.');
header('Content-Type: application/json; charset=utf-8');

checkRole(array('admin', 'editor'));

if (!isset($_FILES['image']) || !is_array($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    ajaxResponse(1, 'Lütfen geçerli bir görsel seçin.');
}

$upload = $_FILES['image'];
if ((int)$upload['size'] < 1 || (int)$upload['size'] > 8 * 1024 * 1024) {
    ajaxResponse(1, 'Görsel en fazla 8 MB olabilir.');
}

$imageInfo = @getimagesize($upload['tmp_name']);
$mime = is_array($imageInfo) && isset($imageInfo['mime']) ? $imageInfo['mime'] : '';
$extensions = array(
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
    'image/gif' => 'gif',
);
if (!isset($extensions[$mime])) {
    ajaxResponse(1, 'Yalnızca JPG, PNG, WebP veya GIF yükleyebilirsiniz.');
}

$directory = PATH_UPLOADS . 'vox-media' . DS;
if (!Filesystem::directoryExists($directory) && !Filesystem::mkdir($directory, true)) {
    ajaxResponse(1, 'Görsel klasörü oluşturulamadı.');
}

$filename = 'vox-' . date('Ymd-His') . '-' . bin2hex(random_bytes(5)) . '.' . $extensions[$mime];
if (!move_uploaded_file($upload['tmp_name'], $directory . $filename)) {
    ajaxResponse(1, 'Görsel yüklenemedi.');
}

ajaxResponse(0, 'Görsel yüklendi.', array('url' => DOMAIN_UPLOADS . 'vox-media/' . $filename));
