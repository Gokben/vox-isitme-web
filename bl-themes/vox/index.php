<?php

declare(strict_types=1);

defined('BLUDIT') || die('Bludit CMS.');

$voxLogin = new Login();
$voxAdminLoggedIn = $voxLogin->isLogged();

$baseUrl = rtrim(Theme::siteUrl(), '/');
$homeUrl = $baseUrl . '/';
$aboutUrl = $baseUrl . '/hakkimizda';
$blogUrl = $baseUrl . '/blog';
$contactUrl = $baseUrl . '/iletisim';
$appointmentUrl = $baseUrl . '/randevu';
$requestPath = rtrim((string)(parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/'), '/');
$blogPath = rtrim((string)(parse_url($blogUrl, PHP_URL_PATH) ?: '/blog'), '/');
$isBlogRoute = $requestPath === $blogPath;
$contactPath = rtrim((string)(parse_url($contactUrl, PHP_URL_PATH) ?: '/iletisim'), '/');
$isContactRoute = $requestPath === $contactPath;
$managedPagePaths = [
    'hakkimizda' => rtrim((string)(parse_url($aboutUrl, PHP_URL_PATH) ?: '/hakkimizda'), '/'),
    'randevu' => rtrim((string)(parse_url($appointmentUrl, PHP_URL_PATH) ?: '/randevu'), '/'),
    'blog' => $blogPath,
    'iletisim' => $contactPath,
];
$voxDisabledPagesFile = PATH_DATABASES . 'vox-disabled-pages.json';
$voxDisabledPages = [];
if (is_file($voxDisabledPagesFile)) {
    $disabledPagesDecoded = json_decode((string)file_get_contents($voxDisabledPagesFile), true);
    if (is_array($disabledPagesDecoded)) {
        $voxDisabledPages = array_values(array_intersect($disabledPagesDecoded, array_keys($managedPagePaths)));
    }
}
$voxCurrentManagedPage = '';
foreach ($managedPagePaths as $managedSlug => $managedPath) {
    if ($requestPath === $managedPath) {
        $voxCurrentManagedPage = $managedSlug;
        break;
    }
}
$voxPageIsEnabled = static function (string $slug) use ($voxDisabledPages): bool {
    return !in_array($slug, $voxDisabledPages, true);
};
$isVoxPageDisabled = $voxCurrentManagedPage !== '' && !$voxPageIsEnabled($voxCurrentManagedPage);
if ($isVoxPageDisabled) {
    header('HTTP/1.0 404 Not Found', true, 404);
} elseif ($isBlogRoute || $isContactRoute) {
    header('HTTP/1.0 200 OK', true, 200);
}

if ($isBlogRoute && !$isVoxPageDisabled) {
$voxBlogVisitorCookie = 'VOX-BLOG-VISITOR';
$voxBlogVisitorId = isset($_COOKIE[$voxBlogVisitorCookie]) ? (string)$_COOKIE[$voxBlogVisitorCookie] : '';
if (!preg_match('/^[a-f0-9]{32}$/', $voxBlogVisitorId)) {
    $voxBlogVisitorId = bin2hex(random_bytes(16));
    setcookie($voxBlogVisitorCookie, $voxBlogVisitorId, [
        'expires' => time() + 31536000,
        'path' => $site->urlPath() ?: '/',
        'secure' => $site->isHTTPS(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}
$voxBlogVisitorHash = hash('sha256', $voxBlogVisitorId . '|' . $site->title());
$voxBlogStatsFile = PATH_DATABASES . 'vox-blog-stats.json';
$voxMutateBlogStats = static function (callable $callback) use ($voxBlogStatsFile) {
    $handle = @fopen($voxBlogStatsFile, 'c+');
    if ($handle === false || !flock($handle, LOCK_EX)) {
        if (is_resource($handle)) {
            fclose($handle);
        }
        return false;
    }
    rewind($handle);
    $raw = stream_get_contents($handle);
    $database = $raw !== false && $raw !== '' ? json_decode($raw, true) : [];
    if (!is_array($database)) {
        $database = [];
    }
    $result = $callback($database);
    $json = json_encode($database, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    if ($json === false) {
        flock($handle, LOCK_UN);
        fclose($handle);
        return false;
    }
    rewind($handle);
    ftruncate($handle, 0);
    $saved = fwrite($handle, $json) !== false;
    fflush($handle);
    flock($handle, LOCK_UN);
    fclose($handle);
    return $saved ? $result : false;
};

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vox_blog_stats_action'])) {
    header('Content-Type: application/json; charset=utf-8');
    $action = (string)$_POST['vox_blog_stats_action'];
    $slug = isset($_POST['slug']) ? trim((string)$_POST['slug']) : '';
    if ($action !== 'toggle-like' || !preg_match('~^[a-z0-9-]{3,180}$~', $slug)) {
        http_response_code(400);
        exit(json_encode(['status' => 1, 'message' => 'Geçersiz işlem.'], JSON_UNESCAPED_UNICODE));
    }
    $blogDatabaseFile = PATH_DATABASES . 'vox-blog.json';
    $blogDatabase = is_file($blogDatabaseFile) ? json_decode((string)file_get_contents($blogDatabaseFile), true) : [];
    $postExists = false;
    if (is_array($blogDatabase)) {
        foreach ($blogDatabase as $post) {
            if (is_array($post) && isset($post['slug']) && hash_equals((string)$post['slug'], $slug)) {
                $postExists = true;
                break;
            }
        }
    }
    if (!$postExists) {
        http_response_code(404);
        exit(json_encode(['status' => 1, 'message' => 'Blog yazısı bulunamadı.'], JSON_UNESCAPED_UNICODE));
    }
    $statsResult = $voxMutateBlogStats(static function (array &$stats) use ($slug, $voxBlogVisitorHash): array {
        if (!isset($stats[$slug]) || !is_array($stats[$slug])) {
            $stats[$slug] = ['visitors' => [], 'likes' => []];
        }
        $stats[$slug]['visitors'] = isset($stats[$slug]['visitors']) && is_array($stats[$slug]['visitors']) ? $stats[$slug]['visitors'] : [];
        $stats[$slug]['likes'] = isset($stats[$slug]['likes']) && is_array($stats[$slug]['likes']) ? $stats[$slug]['likes'] : [];
        $stats[$slug]['visitors'][$voxBlogVisitorHash] = time();
        if (isset($stats[$slug]['likes'][$voxBlogVisitorHash])) {
            unset($stats[$slug]['likes'][$voxBlogVisitorHash]);
            $liked = false;
        } else {
            $stats[$slug]['likes'][$voxBlogVisitorHash] = time();
            $liked = true;
        }
        return ['views' => count($stats[$slug]['visitors']), 'likes' => count($stats[$slug]['likes']), 'liked' => $liked];
    });
    if ($statsResult === false) {
        http_response_code(500);
        exit(json_encode(['status' => 1, 'message' => 'Beğeni kaydedilemedi.'], JSON_UNESCAPED_UNICODE));
    }
    exit(json_encode(['status' => 0] + $statsResult, JSON_UNESCAPED_UNICODE));
}
}
$voxAdminEditUrl = DOMAIN_ADMIN . 'settings';
$voxAdminEditLabel = 'Ana sayfayı düzenle';
$voxBlockPageKey = 'home';

if ($isVoxPageDisabled) {
    include THEME_DIR_PHP . 'disabled-page.php';
} elseif ($isBlogRoute) {
    $voxAdminEditUrl = DOMAIN_ADMIN . 'content';
    $voxAdminEditLabel = 'Blog içeriklerini yönet';
    $voxBlockPageKey = 'blog';
} elseif ($isContactRoute) {
    $voxAdminEditUrl = DOMAIN_ADMIN . 'settings';
    $voxAdminEditLabel = 'İletişim bilgilerini yönet';
    $voxBlockPageKey = 'iletisim';
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
$voxAboutContent = [];
$voxAboutContentFile = PATH_DATABASES . 'vox-about.json';
if (is_file($voxAboutContentFile)) {
    $voxAboutDecoded = json_decode((string)file_get_contents($voxAboutContentFile), true);
    if (is_array($voxAboutDecoded)) {
        $voxAboutContent = $voxAboutDecoded;
    }
}
$voxAboutValue = static function (string $key, string $fallback) use ($voxAboutContent): string {
    $value = isset($voxAboutContent[$key]) ? trim((string)$voxAboutContent[$key]) : '';
    return htmlspecialchars($value !== '' ? $value : $fallback, ENT_QUOTES, 'UTF-8');
};
$appointmentState = ['type' => '', 'message' => ''];
$appointmentValues = [];
$contactState = ['type' => '', 'message' => ''];
$contactValues = [];

if (empty($_SESSION['vox_csrf'])) {
    $_SESSION['vox_csrf'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isContactRoute && isset($_POST['vox_contact'])) {
    foreach (array('name', 'phone', 'email', 'message') as $contactField) {
        $contactValues[$contactField] = trim(strip_tags((string)($_POST[$contactField] ?? '')));
    }

    $token = (string)($_POST['csrf'] ?? '');
    $honeypot = (string)($_POST['website'] ?? '');
    $lastContactRequest = (int)($_SESSION['vox_last_contact_request'] ?? 0);
    $errors = [];

    if ($honeypot !== '') {
        $contactState = ['type' => 'success', 'message' => 'Mesajınız başarıyla gönderildi.'];
    } else {
        if (!hash_equals((string)$_SESSION['vox_csrf'], $token)) {
            $errors[] = 'Oturum doğrulanamadı. Sayfayı yenileyip tekrar deneyin.';
        }
        if (time() - $lastContactRequest < 45) {
            $errors[] = 'Yeni bir mesaj göndermeden önce lütfen kısa bir süre bekleyin.';
        }

        $name = preg_replace('/[\r\n]+/', ' ', $contactValues['name']);
        $phone = $contactValues['phone'];
        $email = $contactValues['email'];
        $message = $contactValues['message'];

        if (mb_strlen($name) < 3 || mb_strlen($name) > 100) {
            $errors[] = 'Lütfen geçerli bir ad ve soyad girin.';
        }
        if (!preg_match('/^[0-9+()\s-]{10,24}$/', $phone)) {
            $errors[] = 'Lütfen geçerli bir telefon numarası girin.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/[\r\n]/', $email)) {
            $errors[] = 'Lütfen geçerli bir e-posta adresi girin.';
        }
        if (mb_strlen($message) < 10 || mb_strlen($message) > 3000) {
            $errors[] = 'Mesajınız 10–3000 karakter arasında olmalıdır.';
        }
        if (!isset($_POST['consent'])) {
            $errors[] = 'Mesajınızın değerlendirilmesi için kişisel veri onayını işaretleyin.';
        }

        if ($errors === []) {
            $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Istanbul'));
            $record = [
                'id' => bin2hex(random_bytes(8)),
                'createdAt' => $now->format(DATE_ATOM),
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'message' => $message,
            ];
            $storageDirectory = PATH_WORKSPACES . 'vox-contact' . DS;
            if (!is_dir($storageDirectory)) {
                @mkdir($storageDirectory, 0750, true);
            }
            $saved = @file_put_contents(
                $storageDirectory . 'messages.jsonl',
                json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL,
                FILE_APPEND | LOCK_EX
            );

            if ($saved === false) {
                $contactState = ['type' => 'error', 'message' => 'Mesajınız kaydedilemedi. Lütfen bizi telefonla arayın.'];
            } else {
                $mailSubject = 'Vox web sitesi iletişim formu - ' . $name;
                $encodedSubject = '=?UTF-8?B?' . base64_encode($mailSubject) . '?=';
                $mailBody = "Yeni iletişim formu mesajı\n\n"
                    . "Tarih: " . $now->format('d.m.Y H:i') . "\n"
                    . "Ad Soyad: " . $name . "\n"
                    . "Telefon: " . $phone . "\n"
                    . "E-posta: " . $email . "\n\n"
                    . "Mesaj:\n" . $message . "\n";
                $mailHeaders = implode("\r\n", [
                    'MIME-Version: 1.0',
                    'Content-Type: text/plain; charset=UTF-8',
                    'From: Vox Web <no-reply@voxisitme.com>',
                    'Reply-To: ' . $email,
                    'X-Mailer: PHP/' . phpversion(),
                ]);
                @mail('bilgi@voxisitme.com', $encodedSubject, $mailBody, $mailHeaders);

                $_SESSION['vox_last_contact_request'] = time();
                $_SESSION['vox_csrf'] = bin2hex(random_bytes(32));
                $contactValues = [];
                $contactState = [
                    'type' => 'success',
                    'message' => 'Mesajınız başarıyla gönderildi. Ekibimiz en kısa sürede sizinle iletişime geçecek.',
                ];
            }
        } else {
            $contactState = ['type' => 'error', 'message' => implode(' ', $errors)];
        }
    }
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
} elseif ($isContactRoute) {
    include THEME_DIR_PHP . 'contact.php';
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
