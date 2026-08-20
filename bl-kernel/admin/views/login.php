<?php defined('BLUDIT') or die('Bludit CMS.');

echo '
<div class="login-logo" aria-label="Vox İşitme">
	<span class="vox-login-wordmark"><b>V</b><img src="' . HTML_PATH_ROOT . 'bl-themes/vox/img/favicon-transparent.png" alt=""><b>X</b></span>
</div>
';

echo Bootstrap::formOpen(array());

echo Bootstrap::formInputHidden(array(
	'name' => 'tokenCSRF',
	'value' => $security->getTokenCSRF()
));

echo '
<div class="form-group">
	<label for="jsusername">E-posta adresi</label>
	<input type="text"
		dir="auto"
		value="' . (isset($_POST['username']) ? Sanitize::html($_POST['username']) : '') . '"
		class="form-control"
		id="jsusername"
		name="username"
		placeholder="E-posta adresiniz veya kullanıcı adınız"
		autocomplete="username"
		required
		autofocus>
</div>

<div class="form-group">
	<label for="jspassword">Şifre</label>
	<div class="password-field">
		<input type="password"
			class="form-control"
			id="jspassword"
			name="password"
			placeholder="Şifreniz"
			autocomplete="current-password"
			required>
		<button class="password-toggle" type="button" aria-label="Şifreyi göster" aria-pressed="false">
			<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/><circle cx="12" cy="12" r="3"/></svg>
		</button>
	</div>
</div>

<div class="form-check">
	<input class="form-check-input" type="checkbox" value="true" id="jsremember" name="remember">
	<label class="form-check-label" for="jsremember">Beni hatırla</label>
</div>

<button type="submit" class="btn btn-login" name="save">Giriş yap</button>
</form>

<p class="login-authorized">Yalnızca yetkili personel erişebilir</p>
';
