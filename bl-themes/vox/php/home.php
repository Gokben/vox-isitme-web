<?php
$heroTitle = trim((string)$site->slogan()) ?: 'Sevdiklerinizin kahkahasını ve hayatın tüm güzelliklerini yeniden duyun.';
$heroDescription = trim((string)$site->description()) ?: 'İşitme testinden size en uygun cihazın seçimine kadar, uzman ekibimiz her adımda güvenilir ve kişisel çözümler sunar.';
?>
<section class="hero" style="--hero-image:url('<?php echo DOMAIN_THEME_IMG; ?>hero-vox-hearing-v14.png')">
    <div class="container hero-content">
        <div class="eyebrow">Seslerin getirdiği mutluluğu yeniden keşfedin</div>
        <h1><?php echo htmlspecialchars($heroTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><?php echo htmlspecialchars($heroDescription, ENT_QUOTES, 'UTF-8'); ?></p>
        <div class="hero-actions"><a class="button" href="#hizmetler">Hizmetleri keşfet <b class="arrow">→</b></a><a class="text-link" href="<?php echo $appointmentUrl; ?>">Ücretsiz test için randevu →</a></div>
    </div>
    <div class="container hero-stats"><div><strong>25+</strong><span>Yıllık deneyim</span></div><div><strong>10K+</strong><span>Mutlu danışan</span></div><div><strong>%98</strong><span>Memnuniyet</span></div></div>
</section>

<section id="hizmetler" class="section container">
    <div class="section-heading"><span class="kicker">İşitme çözümlerimiz</span><h2>İşitme sağlığınız için kapsamlı ve güvenilir hizmetler.</h2><p>Her ihtiyaca aynı çözümü değil, yaşam biçiminize uygun doğru desteği sunuyoruz.</p></div>
    <div class="service-grid">
        <article class="service-card"><span class="service-icon">⌁</span><div><span class="card-number">01</span><h3>Ücretsiz İşitme Testi</h3><p>İşitme seviyenizi uzmanlarımızla hızlı ve güvenilir biçimde değerlendirin.</p></div></article>
        <article class="service-card"><span class="service-icon">✦</span><div><span class="card-number">02</span><h3>Cihaz Danışmanlığı</h3><p>Yaşam tarzınıza ve ihtiyaçlarınıza uygun cihazı birlikte seçelim.</p></div></article>
        <article class="service-card"><span class="service-icon">⚙</span><div><span class="card-number">03</span><h3>Teknik Servis</h3><p>Bakım, ayar ve onarım ihtiyaçlarınız için hızlı teknik destek.</p></div></article>
        <article class="service-card"><span class="service-icon">⌂</span><div><span class="card-number">04</span><h3>Eve Hizmet</h3><p>Hareket kısıtlılığı yaşayan danışanlarımız için evde profesyonel destek.</p></div></article>
    </div>
</section>

<section class="impact"><div class="container impact-inner"><div><span class="kicker kicker-light">Vox farkı</span><h2>Her görüşmede sizi ve ihtiyaçlarınızı gerçekten dinliyoruz.</h2></div><div class="metrics"><div><strong>%98</strong><span>Danışan memnuniyeti</span></div><div><strong>10K+</strong><span>Mutlu danışan</span></div><div><strong>25+</strong><span>Yıllık uzmanlık</span></div><div><strong>5★</strong><span>Özenli hizmet</span></div></div></div></section>

<section class="section container about-preview">
    <div class="about-photo"><img src="<?php echo DOMAIN_THEME_IMG; ?>hakkimizda-vox-danismanlik.png" alt="Vox İşitme uzmanı danışanına işitme cihazını anlatıyor" loading="lazy"><span><strong>25+</strong> yıllık deneyim</span></div>
    <div><span class="kicker">Vox İşitme hakkında</span><h2>İyi duymak, hayata daha yakın olmaktır.</h2><p>İşitme kaybının hayatın sosyal ve duygusal yönlerini nasıl etkilediğini biliyoruz. Bu nedenle teknolojiyi samimi ve nitelikli danışmanlıkla bir araya getiriyoruz.</p><div class="checks"><span>Bireye özel çözümler</span><span>Ücretsiz işitme testi</span><span>Güvenilir teknik servis</span><span>Evde hizmet imkânı</span></div><a class="button" href="<?php echo $aboutUrl; ?>">Bizi yakından tanıyın <b class="arrow">→</b></a></div>
</section>

<section class="why"><div class="section container"><div class="section-heading"><span class="kicker">Neden Vox İşitme?</span><h2>İşitme yolculuğunuzda güvenle ilerleyin.</h2></div><div class="why-grid"><article><span>01</span><h3>Uzman ve anlayışlı ekip</h3><p>İhtiyacınızı dinler, doğru çözüm için sizi acele etmeden yönlendiririz.</p></article><article><span>02</span><h3>Güncel teknolojiler</h3><p>Farklı yaşam tarzlarına uyum sağlayan modern cihaz seçenekleri sunarız.</p></article><article><span>03</span><h3>Erişilebilir destek</h3><p>Şubemizde veya evinizde ihtiyaç duyduğunuz desteği yanınıza getiririz.</p></article></div></div></section>

<section class="cta"><div class="container cta-box"><div><span class="kicker kicker-light">İlk adımı atın</span><h2>İşitme sağlığınız için bugün harekete geçin.</h2><p>Ücretsiz işitme testi ve uzman görüşmesi için size uygun zamanı seçin.</p></div><a class="button" href="<?php echo $appointmentUrl; ?>">Online Randevu Al <b class="arrow">→</b></a></div></section>
