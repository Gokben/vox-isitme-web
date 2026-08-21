<?php defined('BLUDIT') or die('Bludit CMS.');
header('Content-Type: application/json; charset=utf-8');

checkRole(array('admin', 'editor'));

$filename = isset($_POST['filename']) ? (string)$_POST['filename'] : '';
if ($filename === '' || basename($filename) !== $filename || !preg_match('~^[a-zA-Z0-9._-]+$~', $filename)) {
    ajaxResponse(1, 'Geçersiz dosya adı.');
}

$directory = PATH_UPLOADS . 'vox-media' . DS;
$target = $directory . $filename;
if (!is_file($target) || !Sanitize::pathFile($target)) {
    ajaxResponse(1, 'Görsel bulunamadı.');
}

if (!Filesystem::rmfile($target)) {
    ajaxResponse(1, 'Görsel silinemedi.');
}

ajaxResponse(0, 'Görsel silindi.');
