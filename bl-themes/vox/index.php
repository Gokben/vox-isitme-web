<?php

declare(strict_types=1);

defined('BLUDIT') || die('Bludit CMS.');

$voxLogin = new Login();
$voxAdminLoggedIn = $voxLogin->isLogged();

$baseUrl = rtrim(Theme::siteUrl(), '/');
$homeUrl = $baseUrl . '/';
$aboutUrl = $baseUrl . '/hakkimizda';
$blogUrl = $baseUrl . '/blog';
$appointmentUrl = $baseUrl . '/randevu';
$requestPath = rtrim((string)(parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/'), '/');
$blogPath = rtrim((string)(parse_url($blogUrl, PHP_URL_PATH) ?: '/blog'), '/');
$isBlogRoute = $requestPath === $blogPath;
$voxAdminEditUrl = DOMAIN_ADMIN . 'settings';
$voxAdminEditLabel = 'Ana sayfayı düzenle';
$voxBlockPageKey = 'home';

if ($isBlogRoute) {
    $voxAdminEditUrl = DOMAIN_ADMIN . 'content';
    $voxAdminEditLabel = 'Blog içeriklerini yönet';
    $voxBlockPageKey = 'blog';
} elseif ($WHERE_AM_I === 'page' && isset($page) && method_exists($page, 'key')) {
    $voxAdminEditUrl = DOMAIN_ADMIN . 'edit-content/' . rawurlencode((string)$page->key());
    $voxAdminEditLabel = 'Bu sayfayı düzenle';
    $voxBlockPageKey = (string)$page->key();
}

$voxBlocks = [];
$voxBlocksFile = PATH_DATABASES . 'vox-blocks.json';
if (is_file($voxBlocksFile)) {
    $voxBlocksDatabase = json_decode((string)file_get_contents($voxBlocksFile), true);
    if (is_array($voxBlocksDatabase) && isset($voxBlocksDatabase[$voxBlockPageKey]) && is_array($voxBlocksDatabase[$voxBlockPageKey])) {
        $voxBlocks = $voxBlocksDatabase[$voxBlockPageKey];
    }
}

$voxHomeContent = [];
$voxHomeContentFile = PATH_DATABASES . 'vox-home.json';
if (is_file($voxHomeContentFile)) {
    $voxHomeDecoded = json_decode((string)file_get_contents($voxHomeContentFile), true);
    if (is_array($voxHomeDecoded)) {
        $voxHomeContent = $voxHomeDecoded;
    }
}
$voxHomeValue = static function (string $key, string $fallback) use ($voxHomeContent): string {
    $value = isset($voxHomeContent[$key]) ? trim((string)$voxHomeContent[$key]) : '';
    return htmlspecialchars($value !== '' ? $value : $fallback, ENT_QUOTES, 'UTF-8');
};
$appointmentState = ['type' => '', 'message' => ''];
$appointmentValues = [];

if (empty($_SESSION['vox_csrf'])) {
    $_SESSION['vox_csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vox_appointment'])) {
    $appointmentValues = array_map(
        static fn ($value): string => trim(strip_tags((string)$value)),
        $_POST
    );

    $allowedBranches = ['Sefaköy Şubesi (Merkez)', 'Bahçeşehir Şubesi'];
    $allowedTimes = ['09:30', '10:20', '11:10', '13:30', '14:20', '15:10', '16:00', '16:50'];
    $token = (string)($_POST['csrf'] ?? '');
    $honeypot = (string)($_POST['website'] ?? '');
    $lastRequest = (int)($_SESSION['vox_last_request'] ?? 0);
    $errors = [];

    if ($honeypot !== '') {
        $appointmentState = ['type' => 'success', 'message' => 'Randevu talebiniz alındı.'];
    } else {
        if (!hash_equals((string)$_SESSION['vox_csrf'], $token)) {
            $errors[] = 'Oturum doğrulanamadı. Sayfayı yenileyip tekrar deneyin.';
        }
        if (time() - $lastRequest < 30) {
            $errors[] = 'Yeni bir talep göndermeden önce lütfen kısa bir süre bekleyin.';
        }

        $name = $appointmentValues['name'] ?? '';
        $phone = $appointmentValues['phone'] ?? '';
        $email = $appointmentValues['email'] ?? '';
        $branch = $appointmentValues['branch'] ?? '';
        $date = $appointmentValues['date'] ?? '';
        $time = $appointmentValues['time'] ?? '';
        $note = $appointmentValues['note'] ?? '';

        if (mb_strlen($name) < 3 || mb_strlen($name) > 100) {
            $errors[] = 'Lütfen geçerli bir ad ve soyad girin.';
        }
        if (!preg_match('/^[0-9+()\s-]{10,24}$/', $phone)) {
            $errors[] = 'Lütfen geçerli bir telefon numarası girin.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Lütfen geçerli bir e-posta adresi girin.';
        }
        if (!in_array($branch, $allowedBranches, true)) {
            $errors[] = 'Lütfen geçerli bir şube seçin.';
        }
        if (!in_array($time, $allowedTimes, true)) {
            $errors[] = 'Lütfen geçerli bir randevu saati seçin.';
        }

        $selectedDate = DateTimeImmutable::createFromFormat('!Y-m-d', $date, new DateTimeZone('Europe/Istanbul'));
        $today = new DateTimeImmutable('today', new DateTimeZone('Europe/Istanbul'));
        if (!$selectedDate || $selectedDate < $today || (int)$selectedDate->format('w') === 0) {
            $errors[] = 'Lütfen bugünden sonraki geçerli bir çalışma günü seçin.';
        }
        if (!isset($_POST['consent'])) {
            $errors[] = 'Randevu talebi için kişisel veri onayını işaretleyin.';
        }
        if (mb_strlen($note) > 1000) {
            $errors[] = 'Not alanı en fazla 1000 karakter olabilir.';
        }

        if ($errors === []) {
            $storageDirectory = PATH_WORKSPACES . 'vox-appointments' . DS;
            if (!is_dir($storageDirectory)) {
                @mkdir($storageDirectory, 0750, true);
            }

            $record = [
                'id' => bin2hex(random_bytes(8)),
                'createdAt' => (new DateTimeImmutable('now', new DateTimeZone('Europe/Istanbul')))->format(DATE_ATOM),
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'branch' => $branch,
                'date' => $date,
                'time' => $time,
                'note' => $note,
            ];

            $saved = @file_put_contents(
                $storageDirectory . 'requests.jsonl',
                json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL,
                FILE_APPEND | LOCK_EX
            );

            if ($saved === false) {
                $appointmentState = ['type' => 'error', 'message' => 'Talebiniz kaydedilemedi. Lütfen bizi telefonla arayın.'];
            } else {
                $_SESSION['vox_last_request'] = time();
                $_SESSION['vox_csrf'] = bin2hex(random_bytes(32));
                $appointmentValues = [];
                $appointmentState = [
                    'type' => 'success',
                    'message' => 'Randevu talebiniz alındı. Ekibimiz en kısa sürede sizinle iletişime geçecek.',
                ];
            }
        } else {
            $appointmentState = ['type' => 'error', 'message' => implode(' ', $errors)];
        }
    }
}

include THEME_DIR_PHP . 'header.php';

if ($isBlogRoute) {
    include THEME_DIR_PHP . 'blog.php';
} elseif ($WHERE_AM_I === 'page') {
    $slug = method_exists($page, 'slug') ? $page->slug() : '';
    if ($slug === 'randevu') {
        include THEME_DIR_PHP . 'appointment.php';
    } else {
        include THEME_DIR_PHP . 'page.php';
    }
} else {
    include THEME_DIR_PHP . 'home.php';
}

include THEME_DIR_PHP . 'blocks.php';
include THEME_DIR_PHP . 'footer.php';
