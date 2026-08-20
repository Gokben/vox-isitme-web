<?php
$isAbout = method_exists($page, 'slug') && $page->slug() === 'hakkimizda';
?>
<section class="page-hero"><div class="container"><span class="kicker kicker-light"><?php echo $isAbout ? 'Vox İşitme hakkında' : 'Vox İşitme'; ?></span><h1><?php echo $page->title(); ?></h1><?php if ($page->description()): ?><p><?php echo $page->description(); ?></p><?php endif; ?></div></section>

<?php if ($isAbout): ?>
<section class="section container about-story">
    <div class="page-copy">
        <span class="kicker">Size özel çözümler</span>
        <h2>İşitme sağlığınızı önemsiyoruz.</h2>
        <div class="cms-content"><?php echo $page->content(); ?></div>
    </div>
    <div class="about-photo"><img src="<?php echo DOMAIN_THEME_IMG; ?>hakkimizda-vox-danismanlik.png" alt="Vox İşitme danışmanlığı"><span><strong>Kişiye özel</strong> işitme çözümleri</span></div>
</section>
<section class="why"><div class="section container"><div class="section-heading"><span class="kicker">Vox yaklaşımı</span><h2>Her adımda yanınızdayız.</h2></div><div class="why-grid"><article><span>01</span><h3>Doğru cihaz seçimi</h3><p>İşitme kaybının türüne ve derecesine göre en uygun cihazı birlikte seçeriz.</p></article><article><span>02</span><h3>Güncel teknolojiler</h3><p>Bluetooth bağlantılı ve şarj edilebilir çözümlerle deneyiminizi geliştiririz.</p></article><article><span>03</span><h3>Kesintisiz destek</h3><p>Test, teknik servis, eve hizmet ve danışmanlıkla yanınızda oluruz.</p></article></div></div></section>
<?php else: ?>
<article class="section container generic-page"><div class="cms-content"><?php echo $page->content(); ?></div></article>
<?php endif; ?>

<section class="cta"><div class="container cta-box"><div><h2>İşitme sağlığınız için ilk adımı bugün atın.</h2><p>Ücretsiz işitme testi için size uygun zamanı seçin.</p></div><a class="button" href="<?php echo $appointmentUrl; ?>">Randevu Al <b class="arrow">→</b></a></div></section>
