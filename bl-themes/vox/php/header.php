<?php
$viewSlug = !empty($isBlogRoute) ? 'blog' : (!empty($isContactRoute) ? 'iletisim' : (($WHERE_AM_I === 'page' && isset($page) && method_exists($page, 'slug')) ? $page->slug() : ''));
$bodyClass = $WHERE_AM_I === 'home' ? 'is-home' : ($viewSlug === 'randevu' ? 'is-appointment' : ($viewSlug === 'blog' ? 'is-blog' : ($viewSlug === 'iletisim' ? 'is-contact' : 'is-inner')));
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
    <?php elseif (!empty($isContactRoute)): ?>
    <title>İletişim | <?php echo htmlspecialchars($site->title(), ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="description" content="Vox İşitme Sefaköy ve Bahçeşehir şubelerinin adres, telefon ve harita bilgileri.">
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
            <?php if ($WHERE_AM_I === 'home' || $viewSlug === 'hakkimizda'): ?>
            <button class="vox-edit-primary vox-inline-edit-button" type="button" data-vox-inline-edit><?php echo $viewSlug === 'hakkimizda' ? 'Hakkımızda sayfasını düzenle' : 'Ana sayfayı düzenle'; ?></button>
            <button class="vox-inline-save" type="button" data-vox-inline-save hidden>Değişiklikleri kaydet</button>
            <button class="vox-inline-cancel" type="button" data-vox-inline-cancel hidden>Vazgeç</button>
            <?php elseif (!empty($isBlogRoute)): ?>
            <button class="vox-edit-primary vox-blog-manage-button" type="button" data-vox-blog-manage>Blog yazılarını düzenle</button>
            <?php else: ?>
            <a class="vox-edit-primary" href="<?php echo htmlspecialchars($voxAdminEditUrl, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($voxAdminEditLabel, ENT_QUOTES, 'UTF-8'); ?></a>
            <?php endif; ?>
            <?php if (!empty($isBlogRoute)): ?><button class="vox-add-block-button" type="button" data-vox-block-open>+ Blok ekle</button><?php endif; ?>
            <a href="<?php echo DOMAIN_ADMIN; ?>content">Tüm sayfalar</a>
            <a href="<?php echo DOMAIN_ADMIN; ?>dashboard">Yönetim paneli</a>
            <button class="vox-exit-edit-mode" type="button" data-vox-exit-edit-mode>Düzenleme modundan çık</button>
        </nav>
    </div>
</aside>
<button class="vox-preview-return" type="button" data-vox-return-edit-mode>Düzenlemeye dön</button>
<dialog class="vox-block-dialog" data-vox-block-dialog data-endpoint="<?php echo DOMAIN_ADMIN; ?>ajax/vox-blocks" data-upload-endpoint="<?php echo DOMAIN_ADMIN; ?>ajax/vox-home-image" data-page-key="<?php echo htmlspecialchars($voxBlockPageKey, ENT_QUOTES, 'UTF-8'); ?>" data-token="<?php echo htmlspecialchars((string)$security->getTokenCSRF(), ENT_QUOTES, 'UTF-8'); ?>">
    <form method="dialog" class="vox-block-form" data-vox-block-form>
        <div class="vox-block-dialog-head"><div><span>Sayfa oluşturucu</span><h2 data-vox-block-dialog-title>Yeni blok ekle</h2></div><button type="button" aria-label="Kapat" data-vox-block-close>×</button></div>
        <label>Blok türü<select name="blockType" data-vox-block-type><option value="heading">Başlık</option><option value="text">Metin</option><option value="image">Görsel</option><option value="cta">Çağrı / buton alanı</option></select></label>
        <label data-field="title">Başlık<input name="title" type="text" maxlength="160" placeholder="Blok başlığı"></label>
        <label data-field="text">Metin<textarea name="text" rows="5" maxlength="3000" placeholder="İçeriğinizi yazın"></textarea></label>
        <div data-field="image"><label>Bilgisayardan görsel yükle<input name="blockImage" type="file" accept="image/jpeg,image/png,image/webp,image/gif" data-vox-block-image-file></label><div class="vox-image-or"><span>veya</span></div><label>Görsel adresi<input name="imageUrl" type="url" maxlength="1000" placeholder="https://..."></label></div>
        <div class="vox-block-fields-row" data-field="button"><label>Buton yazısı<input name="buttonLabel" type="text" maxlength="80" placeholder="Detaylı bilgi"></label><label>Buton bağlantısı<input name="buttonUrl" type="text" maxlength="1000" placeholder="/randevu"></label></div>
        <p class="vox-block-form-status" data-vox-block-status aria-live="polite"></p>
        <div class="vox-block-form-actions"><button type="button" class="vox-block-cancel" data-vox-block-close>Vazgeç</button><button type="submit" class="vox-block-save" data-vox-block-submit>Bloğu ekle</button></div>
    </form>
</dialog>
<?php if ($WHERE_AM_I === 'home'): ?>
<dialog class="vox-block-dialog vox-image-dialog" data-vox-image-dialog data-upload-endpoint="<?php echo DOMAIN_ADMIN; ?>ajax/vox-home-image">
    <form method="dialog" class="vox-block-form" data-vox-image-form>
        <div class="vox-block-dialog-head"><div><span>Ana sayfa görseli</span><h2>Görseli değiştir</h2></div><button type="button" aria-label="Kapat" data-vox-image-close>×</button></div>
        <div class="vox-image-preview"><img src="" alt="Yeni görsel önizlemesi" data-vox-image-preview></div>
        <label>Bilgisayardan yükle<input name="image" type="file" accept="image/jpeg,image/png,image/webp,image/gif" data-vox-image-file></label>
        <div class="vox-image-or"><span>veya</span></div>
        <label>Görsel adresi<input name="imageUrl" type="url" maxlength="1000" placeholder="https://..." data-vox-image-url></label>
        <p class="vox-block-form-status" data-vox-image-status aria-live="polite"></p>
        <div class="vox-block-form-actions"><button type="button" class="vox-block-cancel" data-vox-image-close>Vazgeç</button><button type="submit" class="vox-block-save">Görseli kullan</button></div>
    </form>
</dialog>
<?php endif; ?>
<?php if (!empty($isBlogRoute)): ?>
<dialog class="vox-block-dialog vox-blog-dialog" data-vox-blog-dialog data-endpoint="<?php echo DOMAIN_ADMIN; ?>ajax/vox-blog" data-upload-endpoint="<?php echo DOMAIN_ADMIN; ?>ajax/vox-home-image" data-token="<?php echo htmlspecialchars((string)$security->getTokenCSRF(), ENT_QUOTES, 'UTF-8'); ?>">
    <form method="dialog" class="vox-block-form" data-vox-blog-form>
        <div class="vox-block-dialog-head"><div><span>Blog yönetimi</span><h2>Blog yazısını düzenle</h2></div><button type="button" aria-label="Kapat" data-vox-blog-close>×</button></div>
        <input name="slug" type="hidden">
        <label>Başlık<input name="title" type="text" maxlength="180" required></label>
        <label>Tarih<input name="date" type="text" maxlength="60" placeholder="20 Ağustos 2026" required></label>
        <label>Özet<textarea name="excerpt" rows="3" maxlength="600" required></textarea></label>
        <label>Yazı içeriği<textarea name="content" rows="10" maxlength="12000" required></textarea></label>
        <label>Görsel açıklaması<input name="alt" type="text" maxlength="180"></label>
        <label>Bilgisayardan yeni görsel yükle<input name="blogImageFile" type="file" accept="image/jpeg,image/png,image/webp,image/gif" data-vox-blog-image-file></label>
        <div class="vox-image-or"><span>veya mevcut görsel adresi</span></div>
        <label>Görsel adresi<input name="image" type="url" maxlength="1000" required></label>
        <p class="vox-block-form-status" data-vox-blog-status aria-live="polite"></p>
        <div class="vox-block-form-actions"><button type="button" class="vox-block-cancel" data-vox-blog-close>Vazgeç</button><button type="submit" class="vox-block-save">Değişiklikleri kaydet</button></div>
    </form>
</dialog>
<?php endif; ?>
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
            <?php if ($voxPageIsEnabled('hakkimizda')): ?><a href="<?php echo $aboutUrl; ?>">Hakkımızda</a><?php endif; ?>
            <a href="<?php echo $homeUrl; ?>#galeri">Galeri</a>
            <?php if ($voxPageIsEnabled('blog')): ?><a href="<?php echo $blogUrl; ?>">Blog</a><?php endif; ?>
            <?php if ($voxPageIsEnabled('iletisim')): ?><a href="<?php echo $contactUrl; ?>">İletişim</a><?php endif; ?>
        </nav>
        <?php if ($viewSlug === 'randevu'): ?>
        <a href="<?php echo $homeUrl; ?>" class="button nav-appointment nav-home" aria-label="Ana sayfaya dön" title="Ana sayfaya dön">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10.8 12 3l9 7.8v9.7a.5.5 0 0 1-.5.5h-5.7v-6.3H9.2V21H3.5a.5.5 0 0 1-.5-.5v-9.7Z"/><path d="m1.7 11.1 10-8.7a.5.5 0 0 1 .6 0l10 8.7"/></svg>
        </a>
        <?php elseif ($voxPageIsEnabled('randevu')): ?><a href="<?php echo $appointmentUrl; ?>" class="button nav-appointment">Randevu Al <b class="arrow">→</b></a><?php endif; ?>
    </div>
</header>
<main id="main-content">
