<section class="page-hero appointment-hero"><div class="container"><span class="kicker kicker-light">Online randevu</span><h1><?php echo $page->title(); ?></h1><p>Ücretsiz işitme testi için size uygun gün ve saati seçin.</p></div></section>
<section class="booking-section"><div class="container booking-shell">
    <?php if ($appointmentState['message'] !== ''): ?><div class="form-status <?php echo $appointmentState['type']; ?>" role="status"><?php echo htmlspecialchars($appointmentState['message'], ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
    <form id="appointment-form" method="post" action="<?php echo htmlspecialchars($appointmentUrl, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="vox_appointment" value="1">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars((string)$_SESSION['vox_csrf'], ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" id="appointment-date" name="date" value="<?php echo htmlspecialchars($appointmentValues['date'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" id="appointment-time" name="time" value="<?php echo htmlspecialchars($appointmentValues['time'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <label class="honeypot" aria-hidden="true">Web sitesi<input type="text" name="website" tabindex="-1" autocomplete="off"></label>
        <div class="booking-top"><div><span class="field-label">Şube</span><select class="control" name="branch" required><option <?php echo ($appointmentValues['branch'] ?? '') === 'Sefaköy Şubesi (Merkez)' ? 'selected' : ''; ?>>Sefaköy Şubesi (Merkez)</option><option <?php echo ($appointmentValues['branch'] ?? '') === 'Bahçeşehir Şubesi' ? 'selected' : ''; ?>>Bahçeşehir Şubesi</option></select></div><div class="live-clock">Şu an saat: <strong id="live-clock">--:--</strong></div></div>
        <div class="booking-grid">
            <div class="calendar-panel"><div class="calendar-head"><button type="button" id="previous-month" aria-label="Önceki ay">‹</button><strong id="calendar-month"></strong><button type="button" id="next-month" aria-label="Sonraki ay">›</button></div><div class="weekdays"><span>PZT</span><span>SAL</span><span>ÇAR</span><span>PER</span><span>CUM</span><span>CMT</span><span>PAZ</span></div><div id="calendar-days" class="calendar-days"></div><h3>Uygun saatler</h3><div id="time-slots" class="time-slots"></div></div>
            <div class="form-grid">
                <label class="full"><span class="field-label">Ad Soyad</span><input class="control" name="name" autocomplete="name" maxlength="100" value="<?php echo htmlspecialchars($appointmentValues['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></label>
                <label><span class="field-label">Telefon</span><input class="control" name="phone" autocomplete="tel" inputmode="tel" maxlength="24" value="<?php echo htmlspecialchars($appointmentValues['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></label>
                <label><span class="field-label">E-posta</span><input class="control" type="email" name="email" autocomplete="email" maxlength="160" value="<?php echo htmlspecialchars($appointmentValues['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required></label>
                <label class="full"><span class="field-label">Not <small>(isteğe bağlı)</small></span><textarea class="control" name="note" maxlength="1000"><?php echo htmlspecialchars($appointmentValues['note'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea></label>
                <label class="full consent"><input type="checkbox" name="consent" value="1" required><span>Kişisel verilerimin randevu talebimin değerlendirilmesi amacıyla işlenmesini kabul ediyorum.</span></label>
                <button class="full button submit-button" type="submit">Randevu Talebi Oluştur <b class="arrow">→</b></button>
            </div>
        </div>
    </form>
</div></section>
