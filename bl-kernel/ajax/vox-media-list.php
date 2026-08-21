<?php defined('BLUDIT') or die('Bludit CMS.');
header('Content-Type: application/json; charset=utf-8');

checkRole(array('admin', 'editor'));

$directory = PATH_UPLOADS . 'vox-media' . DS;
if (!Filesystem::directoryExists($directory) && !Filesystem::mkdir($directory, true)) {
    ajaxResponse(1, 'Medya klasörü oluşturulamadı.');
}

$files = array();
foreach (new DirectoryIterator($directory) as $file) {
    if (!$file->isFile()) {
        continue;
    }
    $extension = Text::lowercase($file->getExtension());
    if (!in_array($extension, array('jpg', 'jpeg', 'png', 'webp', 'gif'), true)) {
        continue;
    }
    $files[] = array(
        'filename' => $file->getFilename(),
        'url' => DOMAIN_UPLOADS . 'vox-media/' . rawurlencode($file->getFilename()),
        'size' => $file->getSize(),
        'modified' => $file->getMTime(),
    );
}

usort($files, static function ($left, $right) {
    return $right['modified'] <=> $left['modified'];
});

ajaxResponse(0, 'Medya listesi.', array('files' => $files));
