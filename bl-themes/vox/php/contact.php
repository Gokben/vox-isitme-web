<section class="page-hero contact-hero">
    <div class="container">
        <span class="kicker kicker-light">Vox İşitme’ye ulaşın</span>
        <h1>Size en yakın şubemizle iletişime geçin.</h1>
        <p>İşitme testi, cihaz danışmanlığı, teknik servis ve randevu konularında ekibimizle görüşebilirsiniz.</p>
    </div>
</section>

<section class="contact-section">
    <div class="container contact-branches">
        <article class="contact-branch-card">
            <div class="contact-branch-info">
                <span class="contact-branch-number">01</span>
                <h2>Sefaköy Şubesi <small>Merkez</small></h2>
                <address>Kartaltepe Mah. Süvari Cad. No:8E<br>Torkam E-5 AVM yanı / çevresi<br>Küçükçekmece / İstanbul</address>
                <div class="contact-links">
                    <a href="tel:+905011438043">☎ +90 501 143 80 43</a>
                    <a href="mailto:bilgi@voxisitme.com">✉ bilgi@voxisitme.com</a>
                </div>
                <a class="button" href="https://www.google.com/maps/search/?api=1&query=Kartaltepe+Mahallesi+Süvari+Caddesi+No+8E+Küçükçekmece+İstanbul" target="_blank" rel="noopener">Yol Tarifi Al <b class="arrow">→</b></a>
            </div>
            <div class="contact-map"><iframe title="Vox İşitme Sefaköy Şubesi haritası" src="https://www.google.com/maps?q=Kartaltepe%20Mahallesi%20S%C3%BCvari%20Caddesi%20No%208E%20K%C3%BC%C3%A7%C3%BCk%C3%A7ekmece%20%C4%B0stanbul&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
        </article>

        <article class="contact-branch-card contact-branch-card-reverse">
            <div class="contact-branch-info">
                <span class="contact-branch-number">02</span>
                <h2>Bahçeşehir Şubesi</h2>
                <address>Bahçeşehir 1. Kısım Mah. Kemal Sunal Cad. No:21C<br>Başakşehir / İstanbul</address>
                <div class="contact-links">
                    <a href="tel:+905010008043">☎ +90 501 000 80 43</a>
                    <a href="mailto:bilgi@voxisitme.com">✉ bilgi@voxisitme.com</a>
                </div>
                <a class="button" href="https://www.google.com/maps/search/?api=1&query=Bahçeşehir+1.+Kısım+Mahallesi+Kemal+Sunal+Caddesi+No+21C+Başakşehir+İstanbul" target="_blank" rel="noopener">Yol Tarifi Al <b class="arrow">→</b></a>
            </div>
            <div class="contact-map"><iframe title="Vox İşitme Bahçeşehir Şubesi haritası" src="https://www.google.com/maps?q=Bah%C3%A7e%C5%9Fehir%201.%20K%C4%B1s%C4%B1m%20Mahallesi%20Kemal%20Sunal%20Caddesi%20No%2021C%20Ba%C5%9Fak%C5%9Fehir%20%C4%B0stanbul&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
        </article>
    </div>
</section>

<section class="contact-form-section" id="iletisim-formu">
    <div class="container contact-form-layout">
        <div>
            <span class="kicker">Bize yazın</span>
            <h2>İletişim formu</h2>
            <p>Sorularınızı ve taleplerinizi form üzerinden iletin. Ekibimiz en kısa sürede sizinle iletişime geçsin.</p>
        </div>
        <form class="contact-form" method="post" action="<?php echo htmlspecialchars($contactUrl, ENT_QUOTES, 'UTF-8'); ?>#iletisim-formu">
            <input type="hidden" name="vox_contact" value="1">
            <input type="hidden" name="csrf" value="<?php echo htmlspecialchars((string)$_SESSION['vox_csrf'], ENT_QUOTES, 'UTF-8'); ?>">
            <label class="honeypot" aria-hidden="true">Web sitesi<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
            <?php if ($contactState['message'] !== ''): ?><div class="form-status <?php echo htmlspecialchars($contactState['type'], ENT_QUOTES, 'UTF-8'); ?>" role="status"><?php echo htmlspecialchars($contactState['message'], ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
            <div class="contact-form-grid">
                <label>Adınız Soyadınız<input type="text" name="name" autocomplete="name" maxlength="100" placeholder="Adınız ve soyadınız" value="<?php echo htmlspecialchars($contactValues['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></label>
                <label>Telefon Numaranız<input type="tel" name="phone" autocomplete="tel" inputmode="tel" maxlength="24" placeholder="05XX XXX XX XX" value="<?php echo htmlspecialchars($contactValues['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></label>
                <label class="full">E-posta Adresiniz<input type="email" name="email" autocomplete="email" maxlength="160" placeholder="ornek@eposta.com" value="<?php echo htmlspecialchars($contactValues['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></label>
                <label class="full">Mesajınız<textarea name="message" rows="5" minlength="10" maxlength="3000" placeholder="Mesajınızı yazın" required><?php echo htmlspecialchars($contactValues['message'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea></label>
                <?php if ($turnstileEnabled): ?>
                <div class="full contact-turnstile"><div class="cf-turnstile" data-sitekey="<?php echo htmlspecialchars($turnstileSiteKey, ENT_QUOTES, 'UTF-8'); ?>" data-action="contact" data-theme="light"></div></div>
                <?php endif; ?>
                <label class="full consent"><input type="checkbox" name="consent" value="1" required><span>Kişisel verilerimin mesajımın değerlendirilmesi ve tarafıma dönüş yapılması amacıyla işlenmesini kabul ediyorum.</span></label>
            </div>
            <button class="button contact-form-submit" type="submit">Mesaj Gönder <b class="arrow">→</b></button>
        </form>
    </div>
</section>

<?php if ($turnstileEnabled): ?><script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script><?php endif; ?>
