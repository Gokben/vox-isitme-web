<?php
$isAbout = method_exists($page, 'slug') && $page->slug() === 'hakkimizda';
$aboutDefaultText = trim(strip_tags((string)$page->content()));
$aboutDefaultImage = DOMAIN_THEME_IMG . 'hakkimizda-vox-danismanlik.png';
$pageHeading = $isAbout ? $voxAboutValue('hero_title', 'Daha İyi Duy, Daha İyi Yaşa!') : $page->title();
$aboutStoryImage = $isAbout ? $voxAboutValue('story_image', $aboutDefaultImage) : '';
$aboutTrustImage = $isAbout ? $voxAboutValue('trust_image', $aboutDefaultImage) : '';
?>
<section class="page-hero"><div class="container"><span class="kicker kicker-light"<?php if ($isAbout): ?> data-vox-edit-field="hero_kicker"<?php endif; ?>><?php echo $isAbout ? $voxAboutValue('hero_kicker', 'Vox İşitme hakkında') : 'Vox İşitme'; ?></span><h1<?php if ($isAbout): ?> data-vox-edit-field="hero_title"<?php endif; ?>><?php echo $pageHeading; ?></h1><?php if ($isAbout || $page->description()): ?><p<?php if ($isAbout): ?> data-vox-edit-field="hero_description"<?php endif; ?>><?php echo $isAbout ? $voxAboutValue('hero_description', (string)$page->description()) : $page->description(); ?></p><?php endif; ?></div></section>

<?php if ($isAbout): ?>
<section class="section container about-story">
    <div class="page-copy">
        <span class="kicker" data-vox-edit-field="story_kicker"><?php echo $voxAboutValue('story_kicker', 'Size özel çözümler'); ?></span>
        <h2 data-vox-edit-field="story_title"><?php echo $voxAboutValue('story_title', 'İşitme sağlığınızı önemsiyoruz.'); ?></h2>
        <div class="cms-content" data-vox-edit-field="story_text"><?php echo $voxAboutValue('story_text', $aboutDefaultText); ?></div>
    </div>
    <div class="about-photo vox-editable-photo" style="background-image:url(&quot;<?php echo $aboutStoryImage; ?>&quot;)" data-vox-edit-image="story_image" data-vox-image-url="<?php echo $aboutStoryImage; ?>"><span><strong data-vox-edit-field="story_badge_title"><?php echo $voxAboutValue('story_badge_title', 'Kişiye özel'); ?></strong><span data-vox-edit-field="story_badge_text"><?php echo $voxAboutValue('story_badge_text', 'işitme çözümleri'); ?></span></span></div>
</section>
<section class="why"><div class="section container"><div class="section-heading"><span class="kicker" data-vox-edit-field="approach_kicker"><?php echo $voxAboutValue('approach_kicker', 'Vox yaklaşımı'); ?></span><h2 data-vox-edit-field="approach_title"><?php echo $voxAboutValue('approach_title', 'İşitme yolculuğunuzun her adımında yanınızdayız.'); ?></h2></div><div class="why-grid"><article><span>01</span><h3 data-vox-edit-field="approach_1_title"><?php echo $voxAboutValue('approach_1_title', 'Doğru cihaz seçimi'); ?></h3><p data-vox-edit-field="approach_1_text"><?php echo $voxAboutValue('approach_1_text', 'İşitme kaybının türüne ve derecesine göre en uygun işitme cihazını seçmenize yardımcı oluruz.'); ?></p></article><article><span>02</span><h3 data-vox-edit-field="approach_2_title"><?php echo $voxAboutValue('approach_2_title', 'Güncel teknolojiler'); ?></h3><p data-vox-edit-field="approach_2_text"><?php echo $voxAboutValue('approach_2_text', 'Yapay zeka destekli, Bluetooth bağlantılı ve şarj edilebilir çözümlerle işitme deneyiminizi geliştiririz.'); ?></p></article><article><span>03</span><h3 data-vox-edit-field="approach_3_title"><?php echo $voxAboutValue('approach_3_title', 'Kesintisiz destek'); ?></h3><p data-vox-edit-field="approach_3_text"><?php echo $voxAboutValue('approach_3_text', 'Ücretsiz işitme testi, yerinde teknik servis, eve hizmet ve cihaz danışmanlığıyla yanınızdayız.'); ?></p></article></div></div></section>
<section class="section container about-story about-story-reverse">
    <div class="about-photo vox-editable-photo" style="background-image:url(&quot;<?php echo $aboutTrustImage; ?>&quot;)" data-vox-edit-image="trust_image" data-vox-image-url="<?php echo $aboutTrustImage; ?>"></div>
    <div class="page-copy"><span class="kicker" data-vox-edit-field="trust_kicker"><?php echo $voxAboutValue('trust_kicker', 'Güvenilir hizmet'); ?></span><h2 data-vox-edit-field="trust_title"><?php echo $voxAboutValue('trust_title', 'Seslerin dünyasına yeniden merhaba deyin.'); ?></h2><p data-vox-edit-field="trust_text_1"><?php echo $voxAboutValue('trust_text_1', 'Dünya çapında tanınmış markalarla çalışarak en yeni işitme cihazı teknolojilerini kullanıcılarımızla buluşturuyoruz. İşitme cihazlarının bakım ve onarım süreçlerinde de hızlı ve güvenilir çözümler sunarak, cihazlarınızın uzun ömürlü ve en yüksek performansla çalışmasını sağlıyoruz.'); ?></p><p data-vox-edit-field="trust_text_2"><?php echo $voxAboutValue('trust_text_2', 'İşitme kaybı yaşayan bireylerin sosyal hayatta daha aktif olmalarını sağlamak, bizim en büyük önceliğimizdir. İşitme kaybını bir engel olmaktan çıkarıp seslerin dünyasına yeniden merhaba demeniz için buradayız.'); ?></p></div>
</section>
<?php else: ?>
<article class="section container generic-page"><div class="cms-content"><?php echo $page->content(); ?></div></article>
<?php endif; ?>

<section class="cta"><div class="container cta-box"><div><h2<?php if ($isAbout): ?> data-vox-edit-field="cta_title"<?php endif; ?>><?php echo $isAbout ? $voxAboutValue('cta_title', 'İşitme sağlığınız için ilk adımı bugün atın.') : 'İşitme sağlığınız için ilk adımı bugün atın.'; ?></h2><p<?php if ($isAbout): ?> data-vox-edit-field="cta_text"<?php endif; ?>><?php echo $isAbout ? $voxAboutValue('cta_text', 'Ücretsiz işitme testi ve uzman görüşmesi için size uygun zamanı seçin.') : 'Ücretsiz işitme testi ve uzman görüşmesi için size uygun zamanı seçin.'; ?></p></div><a class="button" href="<?php echo $appointmentUrl; ?>">Online Randevu Al <b class="arrow">→</b></a></div></section>
