<?php
$viewSlug = !empty($isBlogRoute) ? 'blog' : (($WHERE_AM_I === 'page' && isset($page) && method_exists($page, 'slug')) ? $page->slug() : '');
$bodyClass = $WHERE_AM_I === 'home' ? 'is-home' : ($viewSlug === 'randevu' ? 'is-appointment' : ($viewSlug === 'blog' ? 'is-blog' : 'is-inner'));
if (!empty($voxAdminLoggedIn)) {
    $bodyClass .= ' has-vox-edit-bar';
}
?>
<!doctype html>
<html lang="<?php echo Theme::lang(); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#064b2b">
    <?php if (!empty($isBlogRoute)): ?>
    <title>Blog Yazıları | <?php echo htmlspecialchars($site->title(), ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="İşitme sağlığı, cihaz teknolojileri ve günlük yaşam için Vox İşitme uzmanlarından yararlı bilgiler.">
    <?php else: ?>
    <?php echo Theme::metaTagTitle(); ?>
    <?php echo Theme::metaTagDescription(); ?>
    <?php endif; ?>
    <?php echo Theme::favicon('img/brand-mark.png'); ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?php echo DOMAIN_THEME; ?>css/style.css?v=<?php echo filemtime(THEME_DIR_CSS . 'style.css'); ?>">
    <?php Theme::plugins('siteHead'); ?>
</head>
<body class="<?php echo $bodyClass; ?>">
<?php Theme::plugins('siteBodyBegin'); ?>
<?php if (!empty($voxAdminLoggedIn)): ?>
<aside class="vox-edit-bar" aria-label="Yönetici düzenleme araçları">
    <div class="container vox-edit-bar-inner">
        <span class="vox-edit-status"><b>VOX</b> Düzenleme modu</span>
        <nav aria-label="Hızlı düzenleme">
            <?php if ($WHERE_AM_I === 'home'): ?>
            <button class="vox-edit-primary vox-inline-edit-button" type="button" data-vox-inline-edit>Ana sayfayı düzenle</button>
            <button class="vox-inline-save" type="button" data-vox-inline-save hidden>Değişiklikleri kaydet</button>
            <button class="vox-inline-cancel" type="button" data-vox-inline-cancel hidden>Vazgeç</button>
            <?php else: ?>
            <a class="vox-edit-primary" href="<?php echo htmlspecialchars($voxAdminEditUrl, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($voxAdminEditLabel, ENT_QUOTES, 'UTF-8'); ?></a>
            <?php endif; ?>
            <button class="vox-add-block-button" type="button" data-vox-block-open>+ Blok ekle</button>
            <a href="<?php echo DOMAIN_ADMIN; ?>content">Tüm sayfalar</a>
            <a href="<?php echo DOMAIN_ADMIN; ?>dashboard">Yönetim paneli</a>
        </nav>
    </div>
</aside>
<dialog class="vox-block-dialog" data-vox-block-dialog data-endpoint="<?php echo DOMAIN_ADMIN; ?>ajax/vox-blocks" data-page-key="<?php echo htmlspecialchars($voxBlockPageKey, ENT_QUOTES, 'UTF-8'); ?>" data-token="<?php echo htmlspecialchars((string)$security->getTokenCSRF(), ENT_QUOTES, 'UTF-8'); ?>">
    <form method="dialog" class="vox-block-form" data-vox-block-form>
        <div class="vox-block-dialog-head"><div><span>Sayfa oluşturucu</span><h2>Yeni blok ekle</h2></div><button type="button" aria-label="Kapat" data-vox-block-close>×</button></div>
        <label>Blok türü<select name="blockType" data-vox-block-type><option value="heading">Başlık</option><option value="text">Metin</option><option value="image">Görsel</option><option value="cta">Çağrı / buton alanı</option></select></label>
        <label data-field="title">Başlık<input name="title" type="text" maxlength="160" placeholder="Blok başlığı"></label>
        <label data-field="text">Metin<textarea name="text" rows="5" maxlength="3000" placeholder="İçeriğinizi yazın"></textarea></label>
        <label data-field="image">Görsel adresi<input name="imageUrl" type="url" maxlength="1000" placeholder="https://..."></label>
        <div class="vox-block-fields-row" data-field="button"><label>Buton yazısı<input name="buttonLabel" type="text" maxlength="80" placeholder="Detaylı bilgi"></label><label>Buton bağlantısı<input name="buttonUrl" type="text" maxlength="1000" placeholder="/randevu"></label></div>
        <p class="vox-block-form-status" data-vox-block-status aria-live="polite"></p>
        <div class="vox-block-form-actions"><button type="button" class="vox-block-cancel" data-vox-block-close>Vazgeç</button><button type="submit" class="vox-block-save">Bloğu ekle</button></div>
    </form>
</dialog>
<?php endif; ?>
<a class="skip-link" href="#main-content">İçeriğe geç</a>
<header class="site-header">
    <div class="container nav">
        <a class="brand site-brand" href="<?php echo $homeUrl; ?>" aria-label="Vox İşitme ana sayfa">
            <span class="brand-mark">V<img src="<?php echo DOMAIN_THEME_IMG; ?>brand-mark.png?v=<?php echo filemtime(THEME_DIR_IMG . 'brand-mark.png'); ?>" alt="">X</span>
            <span class="brand-label">İŞİTME CİHAZLARI</span>
        </a>
        <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="main-navigation"><span></span><span></span><span></span><b class="sr-only">Menüyü aç</b></button>
        <nav id="main-navigation" class="main-nav" aria-label="Ana menü">
            <a href="<?php echo $homeUrl; ?>">Ana Sayfa</a>
            <a href="<?php echo $aboutUrl; ?>">Hakkımızda</a>
            <a href="<?php echo $homeUrl; ?>#galeri">Galeri</a>
            <a href="<?php echo $blogUrl; ?>">Blog</a>
            <a href="<?php echo $homeUrl; ?>#iletisim">İletişim</a>
        </nav>
        <a href="<?php echo $appointmentUrl; ?>" class="button nav-appointment">Randevu Al <b class="arrow">→</b></a>
    </div>
</header>
<main id="main-content">
