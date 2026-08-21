<?php
$heroTitle = trim((string)$site->slogan()) ?: 'Sevdiklerinizin kahkasını, doğanın seslerini ve hayatın tüm güzelliklerini yeniden duymaya hazır olun.';
$heroDescription = trim((string)$site->description()) ?: 'İşitme testinden size en uygun cihazın seçimine kadar, uzman ekibimiz her adımda güvenilir ve kişisel çözümler sunar.';
?>
<section class="hero" style="--hero-image:url('<?php echo DOMAIN_THEME_IMG; ?>hero-vox-hearing-v14.png')">
    <div class="container hero-content">
        <div class="eyebrow" data-vox-edit-field="hero_eyebrow"><?php echo $voxHomeValue('hero_eyebrow', 'Seslerin Getirdiği Mutluluğu Yeniden Keşfedin'); ?></div>
        <h1 class="hero-slogan" data-vox-edit-field="hero_title"><?php echo $voxHomeValue('hero_title', $heroTitle); ?></h1>
        <p data-vox-edit-field="hero_description"><?php echo $voxHomeValue('hero_description', $heroDescription); ?></p>
        <div class="hero-actions"><a class="button" href="#hizmetler">Hizmetlerimizi Keşfedin <b class="arrow">→</b></a></div>
    </div>
    <div class="container hero-stats"><div><strong>25+</strong><span>Yıllık deneyim</span></div><div><strong>10K+</strong><span>Mutlu danışan</span></div></div>
</section>

<section id="hizmetler" class="section container">
    <div class="section-heading"><span class="kicker" data-vox-edit-field="services_kicker"><?php echo $voxHomeValue('services_kicker', 'İşitme çözümlerimiz'); ?></span><h2 data-vox-edit-field="services_title"><?php echo $voxHomeValue('services_title', 'İşitme sağlığınız için kapsamlı ve güvenilir hizmetler.'); ?></h2></div>
    <div class="service-grid">
        <article class="service-card"><span class="service-icon">⌁</span><div><span class="card-number">01</span><h3 data-vox-edit-field="service_1_title"><?php echo $voxHomeValue('service_1_title', 'Ücretsiz İşitme Testi'); ?></h3><p data-vox-edit-field="service_1_text"><?php echo $voxHomeValue('service_1_text', 'İşitme seviyenizi uzmanlarımızla hızlı ve güvenilir biçimde değerlendirin.'); ?></p></div></article>
        <article class="service-card"><span class="service-icon">✦</span><div><span class="card-number">02</span><h3 data-vox-edit-field="service_2_title"><?php echo $voxHomeValue('service_2_title', 'Cihaz Danışmanlığı'); ?></h3><p data-vox-edit-field="service_2_text"><?php echo $voxHomeValue('service_2_text', 'Yaşam tarzınıza ve ihtiyaçlarınıza uygun cihazı birlikte seçelim.'); ?></p></div></article>
        <article class="service-card"><span class="service-icon">⚙</span><div><span class="card-number">03</span><h3 data-vox-edit-field="service_3_title"><?php echo $voxHomeValue('service_3_title', 'Teknik Servis'); ?></h3><p data-vox-edit-field="service_3_text"><?php echo $voxHomeValue('service_3_text', 'Bakım, ayar ve onarım ihtiyaçlarınız için hızlı teknik destek.'); ?></p></div></article>
        <article class="service-card"><span class="service-icon">⌂</span><div><span class="card-number">04</span><h3 data-vox-edit-field="service_4_title"><?php echo $voxHomeValue('service_4_title', 'Eve Hizmet Avantajı'); ?></h3><p data-vox-edit-field="service_4_text"><?php echo $voxHomeValue('service_4_text', 'Hareket kısıtlılığı yaşayan danışanlarımız için evde profesyonel destek.'); ?></p></div></article>
    </div>
</section>

<section class="impact"><div class="container impact-inner"><div><span class="kicker kicker-light" data-vox-edit-field="impact_kicker"><?php echo $voxHomeValue('impact_kicker', 'Vox farkı'); ?></span><h2 data-vox-edit-field="impact_title"><?php echo $voxHomeValue('impact_title', 'Her görüşmede, sizi ve ihtiyaçlarınızı gerçekten dinliyoruz.'); ?></h2></div><div class="metrics"><div><strong>%98</strong><span>Danışan memnuniyeti</span></div><div><strong>10K+</strong><span>Mutlu müşteri</span></div><div><strong>25+</strong><span>Yıllık uzmanlık</span></div><div><strong>5★</strong><span>Özenli hizmet yaklaşımı</span></div></div></div></section>

<section id="hakkimizda" class="section container about-preview">
    <div class="about-photo"><img src="<?php echo DOMAIN_THEME_IMG; ?>hakkimizda-vox-danismanlik.png" alt="Vox İşitme uzmanı danışanına işitme cihazını anlatıyor" loading="lazy"><span><strong>25+</strong> yıllık deneyim</span></div>
    <div><span class="kicker" data-vox-edit-field="about_kicker"><?php echo $voxHomeValue('about_kicker', 'Vox İşitme hakkında'); ?></span><h2 data-vox-edit-field="about_title"><?php echo $voxHomeValue('about_title', 'İyi duymak, hayata daha yakın olmaktır.'); ?></h2><p data-vox-edit-field="about_text"><?php echo $voxHomeValue('about_text', 'Vox İşitme Merkezi olarak işitme kaybının hayatın sosyal ve duygusal yönlerini nasıl etkilediğini biliyoruz. Bu nedenle, teknolojiyi samimi ve nitelikli danışmanlıkla bir araya getiriyoruz.'); ?></p><div class="checks"><span>Bireye özel çözümler</span><span>Ücretsiz işitme testi</span><span>Güvenilir teknik servis</span><span>Evde hizmet imkânı</span></div><a class="button" href="<?php echo $aboutUrl; ?>">Vox’u Tanıyın <b class="arrow">→</b></a></div>
</section>

<section id="neden-vox" class="why"><div class="section container"><div class="section-heading"><span class="kicker" data-vox-edit-field="why_kicker"><?php echo $voxHomeValue('why_kicker', 'Neden Vox İşitme?'); ?></span><h2 data-vox-edit-field="why_title"><?php echo $voxHomeValue('why_title', 'İşitme yolculuğunuzda güvenle ilerlemeniz için buradayız.'); ?></h2></div><div class="why-grid"><article><span>01</span><h3 data-vox-edit-field="why_1_title"><?php echo $voxHomeValue('why_1_title', 'Uzman ve anlayışlı ekip'); ?></h3><p data-vox-edit-field="why_1_text"><?php echo $voxHomeValue('why_1_text', 'İhtiyacınızı dinler, doğru çözüm için sizi acele etmeden yönlendiririz.'); ?></p></article><article><span>02</span><h3 data-vox-edit-field="why_2_title"><?php echo $voxHomeValue('why_2_title', 'Güncel işitme teknolojileri'); ?></h3><p data-vox-edit-field="why_2_text"><?php echo $voxHomeValue('why_2_text', 'Farklı yaşam tarzlarına uyum sağlayan modern cihaz seçenekleri sunarız.'); ?></p></article><article><span>03</span><h3 data-vox-edit-field="why_3_title"><?php echo $voxHomeValue('why_3_title', 'Rahat ve erişilebilir destek'); ?></h3><p data-vox-edit-field="why_3_text"><?php echo $voxHomeValue('why_3_text', 'Şubemizde veya evinizde, ihtiyacınız olan teknik ve danışmanlık desteği yanınızda.'); ?></p></article></div></div></section>

<span id="galeri"></span><span id="blog"></span>
<section class="working-hours-section">
    <div class="container working-hours-layout">
        <div class="working-hours-intro">
            <span class="kicker" data-vox-edit-field="hours_kicker"><?php echo $voxHomeValue('hours_kicker', 'Ziyaretinizi planlayın'); ?></span>
            <h2 data-vox-edit-field="hours_title"><?php echo $voxHomeValue('hours_title', 'Size uygun zamanda yanınızdayız.'); ?></h2>
            <p data-vox-edit-field="hours_text"><?php echo $voxHomeValue('hours_text', 'İşitme testi, cihaz danışmanlığı ve teknik destek için çalışma saatlerimiz içinde şubelerimizi ziyaret edebilirsiniz.'); ?></p>
            <a class="button" href="<?php echo $appointmentUrl; ?>">Randevu Oluştur <b class="arrow">→</b></a>
        </div>
        <div class="working-hours-card">
            <div class="working-hours-head"><h3 data-vox-edit-field="hours_card_title"><?php echo $voxHomeValue('hours_card_title', 'Çalışma Saatleri'); ?></h3><span class="working-hours-clock" aria-hidden="true"></span></div>
            <div class="working-hours-divider"></div>
            <dl>
                <div><dt>Pazartesi – Cuma</dt><dd data-vox-edit-field="hours_weekday"><?php echo $voxHomeValue('hours_weekday', '09:00 – 18:00'); ?></dd></div>
                <div><dt>Cumartesi</dt><dd data-vox-edit-field="hours_saturday"><?php echo $voxHomeValue('hours_saturday', '10:00 – 17:00'); ?></dd></div>
                <div><dt>Pazar</dt><dd data-vox-edit-field="hours_sunday"><?php echo $voxHomeValue('hours_sunday', 'Kapalı'); ?></dd></div>
            </dl>
        </div>
    </div>
</section>
<section id="randevu" class="cta"><div class="container cta-box"><div><h2 data-vox-edit-field="cta_title"><?php echo $voxHomeValue('cta_title', 'İşitme sağlığınız için ilk adımı bugün atın.'); ?></h2><p data-vox-edit-field="cta_text"><?php echo $voxHomeValue('cta_text', 'Ücretsiz işitme testi ve uzman görüşmesi için size uygun zamanı seçin.'); ?></p></div><a class="button" href="<?php echo $appointmentUrl; ?>">Online Randevu Al <b class="arrow">→</b></a></div></section>
