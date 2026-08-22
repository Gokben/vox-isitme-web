<section class="page-hero devices-hero">
    <div class="container">
        <span class="kicker kicker-light">İşitme cihazı kullanım rehberi</span>
        <h1>Cihazınızdan en iyi verimi alın.</h1>
        <p>Günlük kullanım, bakım ve koruma için uygulaması kolay temel adımları bir araya getirdik.</p>
    </div>
</section>

<section class="devices-guide-section">
    <div class="container">
        <div class="devices-intro">
            <div>
                <span class="kicker">Günlük rehber</span>
                <h2>Küçük alışkanlıklar, daha rahat bir işitme deneyimi.</h2>
            </div>
            <p>Her işitme cihazının özellikleri farklıdır. Cihazınızın kullanım kılavuzu ve işitme uzmanınızın size özel önerileri her zaman önceliklidir.</p>
        </div>

        <div class="devices-guide-grid">
            <article class="device-guide-card">
                <figure class="device-guide-image"><img src="<?php echo DOMAIN_THEME_IMG; ?>devices/01-first-days.webp" alt="İşitme cihazına alışma sürecindeki kullanıcı" width="900" height="600" loading="lazy"></figure>
                <span class="device-guide-number">01</span>
                <h3>İlk günler ve alışma süreci</h3>
                <p>Yeni seslere alışmak zaman alabilir. Cihazınızı uzmanınızın önerdiği düzende kullanın; sakin ortamlardan başlayıp farklı dinleme ortamlarına kademeli olarak geçin.</p>
            </article>
            <article class="device-guide-card">
                <figure class="device-guide-image"><img src="<?php echo DOMAIN_THEME_IMG; ?>devices/02-inserting.webp" alt="İşitme cihazının kulağa doğru şekilde takılması" width="900" height="600" loading="lazy"></figure>
                <span class="device-guide-number">02</span>
                <h3>Takma ve çıkarma</h3>
                <p>Elleriniz temiz ve kuru olsun. Sağ-sol işaretlerini kontrol edin, cihazı zorlamadan yerleştirin. Rahatsızlık veya baskı hissederseniz kullanımı durdurup uzmanınıza danışın.</p>
            </article>
            <article class="device-guide-card">
                <figure class="device-guide-image"><img src="<?php echo DOMAIN_THEME_IMG; ?>devices/03-charging.webp" alt="Şarj ünitesindeki işitme cihazları" width="900" height="600" loading="lazy"></figure>
                <span class="device-guide-number">03</span>
                <h3>Şarj ve pil kullanımı</h3>
                <p>Şarj edilebilir cihazlarda üreticinin şarj ünitesini kullanın ve düzenli bir şarj rutini oluşturun. Değiştirilebilir pilleri çocuklardan ve evcil hayvanlardan uzak tutun.</p>
            </article>
            <article class="device-guide-card">
                <figure class="device-guide-image"><img src="<?php echo DOMAIN_THEME_IMG; ?>devices/04-cleaning.webp" alt="İşitme cihazının kuru bezle temizlenmesi" width="900" height="600" loading="lazy"></figure>
                <span class="device-guide-number">04</span>
                <h3>Günlük temizlik</h3>
                <p>Cihazın dışını yumuşak ve kuru bir bezle silin. Elektronik gövdede su, alkol veya çözücü kullanmayın. Filtre, dome ve kalıp bakımını cihazınıza uygun şekilde yapın.</p>
            </article>
            <article class="device-guide-card">
                <figure class="device-guide-image"><img src="<?php echo DOMAIN_THEME_IMG; ?>devices/05-moisture.webp" alt="İşitme cihazının nemden uzakta koruyucu kutuda saklanması" width="900" height="600" loading="lazy"></figure>
                <span class="device-guide-number">05</span>
                <h3>Nemden ve ısıdan koruma</h3>
                <p>Duş, yüzme ve yoğun su teması öncesinde cihazı çıkarın. Saç spreyi ve kozmetik ürünlerini cihazı takmadan önce uygulayın; cihazı doğrudan güneşte veya sıcak araçta bırakmayın.</p>
            </article>
            <article class="device-guide-card">
                <figure class="device-guide-image"><img src="<?php echo DOMAIN_THEME_IMG; ?>devices/06-troubleshooting.webp" alt="İşitme cihazında basit sorun kontrolü yapan kullanıcı" width="900" height="600" loading="lazy"></figure>
                <span class="device-guide-number">06</span>
                <h3>Sorun olduğunda</h3>
                <p>Ses gelmiyorsa pil veya şarjı, açma durumunu, ses çıkışını ve kulak kiri filtresini kontrol edin. Sorun sürerse cihazı zorlamadan teknik destek alın.</p>
            </article>
        </div>

        <div class="devices-check-panel">
            <div>
                <span class="kicker">Hızlı kontrol</span>
                <h2>Cihaz çalışmıyorsa önce bunlara bakın.</h2>
            </div>
            <ul>
                <li>Pil dolu mu veya cihaz yeterince şarj edildi mi?</li>
                <li>Cihaz açık mı, doğru program ve ses seviyesi seçili mi?</li>
                <li>Ses çıkışı, filtre, dome ya da tüp tıkalı mı?</li>
                <li>Telefon bağlantısı kullanılıyorsa Bluetooth açık mı?</li>
            </ul>
        </div>

        <aside class="devices-support-box">
            <div>
                <span>Uzman desteği</span>
                <h2>Rahatsızlık varsa kullanmaya ara verin.</h2>
                <p>Kulakta ağrı, tahriş, akıntı, ani işitme değişikliği veya cihazda hasar fark ederseniz cihazı kullanmayı bırakın ve bir KBB hekimi ya da işitme uzmanıyla görüşün.</p>
            </div>
            <div class="devices-support-actions">
                <?php if ($voxPageIsEnabled('randevu')): ?><a class="button" href="<?php echo $appointmentUrl; ?>">Randevu Al <b class="arrow">→</b></a><?php endif; ?>
                <?php if ($voxPageIsEnabled('iletisim')): ?><a class="devices-text-link" href="<?php echo $contactUrl; ?>">Şubelerimize ulaşın</a><?php endif; ?>
            </div>
        </aside>
    </div>
</section>
