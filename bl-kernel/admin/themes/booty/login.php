<!DOCTYPE html>
<html lang="tr">
<head>
  <title><?php echo Sanitize::html($site->title()) ?> - Giriş</title>
  <meta charset="<?php echo CHARSET ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="robots" content="noindex,nofollow">
  <link rel="icon" type="image/png" sizes="64x64" href="<?php echo HTML_PATH_CORE_IMG . 'favicon.png?v=' . filemtime(PATH_KERNEL . 'img' . DS . 'favicon.png') ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
  <?php
  echo Theme::cssBootstrap();
  echo Theme::css(array('bludit.css', 'bludit.bootstrap.css'), DOMAIN_ADMIN_THEME_CSS);
  ?>
  <style>
    * { box-sizing: border-box; }
    body.login {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 24px;
      color: #20242b;
      background: #e9ecef;
      font-family: "DM Sans", Arial, sans-serif;
    }
    .login-container { width: 100%; max-width: 424px; }
    .login-card {
      width: 100%;
      padding: 42px 43px 44px;
      background: #fff;
      border-radius: 22px;
      box-shadow: 0 18px 50px rgba(21, 32, 45, .12);
    }
    .login-logo { display: flex; justify-content: center; margin: 0 0 28px; }
    .vox-login-wordmark {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 4px;
      color: #050505;
      font: 800 82px/.8 Manrope, Arial, sans-serif;
      letter-spacing: -9px;
    }
    .vox-login-wordmark b { font-weight: 800; }
    .vox-login-wordmark img {
      width: 68px;
      height: 68px;
      margin: 0 5px;
      border-radius: 50%;
      object-fit: cover;
    }
    .login-card .form-group { margin-bottom: 21px; }
    .login-card .form-group label {
      display: block;
      margin: 0 0 11px;
      color: #20242b;
      font-size: 14px;
      font-weight: 600;
    }
    .login-card .form-control {
      width: 100%;
      height: 47px;
      padding: 0 15px;
      color: #20242b;
      background: #eaf1fc;
      border: 1px solid #d4deec;
      border-radius: 8px;
      box-shadow: none;
      font: 500 13px "DM Sans", Arial, sans-serif;
    }
    .login-card .form-control:focus {
      background: #eef4fd;
      border-color: #69b97e;
      box-shadow: 0 0 0 3px rgba(32, 169, 76, .13);
    }
    .login-card .form-control::placeholder { color: #7e8b9d; opacity: 1; }
    .password-field { position: relative; }
    .password-field .form-control { padding-right: 52px; }
    .password-toggle {
      position: absolute;
      top: 50%;
      right: 8px;
      width: 38px;
      height: 38px;
      display: grid;
      place-items: center;
      padding: 0;
      color: #74859a;
      background: transparent;
      border: 0;
      transform: translateY(-50%);
      cursor: pointer;
    }
    .password-toggle svg { width: 23px; height: 23px; fill: none; stroke: currentColor; stroke-width: 2; }
    .login-card .form-check {
      display: flex;
      align-items: center;
      min-height: 22px;
      margin: 1px 0 25px;
      padding: 0;
    }
    .login-card .form-check-input {
      position: static;
      width: 18px;
      height: 18px;
      margin: 0 10px 0 0;
      accent-color: #20a94c;
    }
    .login-card .form-check-label { margin: 0; color: #303640; font-size: 13px; }
    .login-card .btn-login {
      width: 100%;
      height: 48px;
      padding: 0 18px;
      color: #fff;
      background: #20a94c;
      border: 0;
      border-radius: 8px;
      box-shadow: none;
      font: 700 14px "DM Sans", Arial, sans-serif;
      cursor: pointer;
      transition: background .2s, transform .2s;
    }
    .login-card .btn-login:hover { color: #fff; background: #178e3e; transform: translateY(-1px); }
    .login-card .btn-login:focus { box-shadow: 0 0 0 4px rgba(32, 169, 76, .18); }
    .login-authorized { margin: 27px 0 0; color: #7a8190; font-size: 16px; text-align: center; }
    .login-alert {
      position: fixed;
      top: 20px;
      left: 50%;
      z-index: 1050;
      width: min(424px, calc(100% - 32px));
      padding: 13px 18px;
      border: 0;
      border-radius: 9px;
      transform: translateX(-50%);
      font-size: 14px;
      font-weight: 600;
    }
    .login-alert.alert-danger { color: #7a221d; background: #ffebe9; }
    .login-alert.alert-success { color: #075d2b; background: #e6f7ec; }
    @media (max-width: 480px) {
      body.login { align-items: stretch; padding: 0; background: #fff; }
      .login-container { max-width: none; }
      .login-card { min-height: 100vh; padding: 42px 30px; border-radius: 0; box-shadow: none; }
      .vox-login-wordmark { font-size: 70px; }
      .vox-login-wordmark img { width: 58px; height: 58px; }
    }
  </style>
  <?php
  echo Theme::jquery();
  echo Theme::jsBootstrap();
  Theme::plugins('loginHead');
  ?>
</head>
<body class="login">
  <?php Theme::plugins('loginBodyBegin') ?>
  <?php if (Alert::defined()): ?>
    <div id="login-alert" class="login-alert alert <?php echo (Alert::status() == ALERT_STATUS_FAIL) ? 'alert-danger' : 'alert-success' ?>">
      <?php echo Alert::get() ?>
    </div>
    <script>setTimeout(function(){var alert=document.getElementById('login-alert');if(alert){alert.style.display='none';}}, <?php echo ALERT_DISAPPEAR_IN * 1000 ?>);</script>
  <?php endif; ?>
  <div class="login-container">
    <div class="login-card">
      <?php
      if (Sanitize::pathFile(PATH_ADMIN_VIEWS, $layout['view'] . '.php')) {
        include(PATH_ADMIN_VIEWS . $layout['view'] . '.php');
      }
      ?>
    </div>
  </div>
  <script>
    (function () {
      var button = document.querySelector('.password-toggle');
      var input = document.getElementById('jspassword');
      if (!button || !input) return;
      button.addEventListener('click', function () {
        var visible = input.type === 'text';
        input.type = visible ? 'password' : 'text';
        button.setAttribute('aria-pressed', visible ? 'false' : 'true');
        button.setAttribute('aria-label', visible ? 'Şifreyi göster' : 'Şifreyi gizle');
      });
    }());
  </script>
  <?php Theme::plugins('loginBodyEnd') ?>
</body>
</html>
