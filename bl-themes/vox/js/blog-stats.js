(function () {
  'use strict';

  var button = document.querySelector('[data-vox-blog-like]');
  if (!button) return;

  var likeCount = button.querySelector('[data-vox-like-count]');
  var label = button.querySelector('[data-vox-like-label]');
  var viewCount = document.querySelector('[data-vox-view-count]');
  var status = document.querySelector('[data-vox-blog-stats-status]');

  button.addEventListener('click', function () {
    var data = new FormData();
    data.set('vox_blog_stats_action', 'toggle-like');
    data.set('slug', button.dataset.slug);
    button.disabled = true;
    status.textContent = 'Kaydediliyor…';

    fetch(button.dataset.endpoint, { method: 'POST', body: data, credentials: 'same-origin' })
      .then(function (response) { return response.json(); })
      .then(function (result) {
        if (Number(result.status) !== 0) throw new Error(result.message || 'Beğeni kaydedilemedi.');
        button.classList.toggle('liked', Boolean(result.liked));
        button.setAttribute('aria-pressed', result.liked ? 'true' : 'false');
        likeCount.textContent = String(result.likes);
        label.textContent = result.liked ? 'Beğenildi' : 'Beğen';
        if (viewCount) viewCount.textContent = String(result.views);
        status.textContent = result.liked ? 'Teşekkürler.' : 'Beğeniniz geri alındı.';
      })
      .catch(function (error) { status.textContent = error.message || 'Beğeni kaydedilemedi.'; })
      .finally(function () { button.disabled = false; });
  });
}());
