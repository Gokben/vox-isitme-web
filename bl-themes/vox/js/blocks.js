(function () {
  'use strict';

  var dialog = document.querySelector('[data-vox-block-dialog]');
  var openButton = document.querySelector('[data-vox-block-open]');
  if (!dialog || !openButton) return;

  var form = dialog.querySelector('[data-vox-block-form]');
  var typeSelect = dialog.querySelector('[data-vox-block-type]');
  var status = dialog.querySelector('[data-vox-block-status]');
  var endpoint = dialog.dataset.endpoint;
  var pageKey = dialog.dataset.pageKey;
  var token = dialog.dataset.token;

  function updateFields() {
    var type = typeSelect.value;
    dialog.querySelector('[data-field="title"]').hidden = type === 'text';
    dialog.querySelector('[data-field="text"]').hidden = type === 'heading' || type === 'image';
    dialog.querySelector('[data-field="image"]').hidden = type !== 'image';
    dialog.querySelector('[data-field="button"]').hidden = type !== 'cta';
  }

  openButton.addEventListener('click', function () {
    status.textContent = '';
    status.className = 'vox-block-form-status';
    updateFields();
    dialog.showModal();
  });

  dialog.querySelectorAll('[data-vox-block-close]').forEach(function (button) {
    button.addEventListener('click', function () { dialog.close(); });
  });

  typeSelect.addEventListener('change', updateFields);

  form.addEventListener('submit', function (event) {
    event.preventDefault();
    var submit = form.querySelector('.vox-block-save');
    var data = new FormData(form);
    data.set('action', 'add');
    data.set('pageKey', pageKey);
    data.set('tokenCSRF', token);
    submit.disabled = true;
    status.textContent = 'Blok kaydediliyor…';

    fetch(endpoint, { method: 'POST', body: data, credentials: 'same-origin' })
      .then(function (response) { return response.json(); })
      .then(function (result) {
        if (Number(result.status) !== 0) throw new Error(result.message || 'Blok eklenemedi.');
        status.className = 'vox-block-form-status success';
        status.textContent = 'Blok eklendi. Sayfa yenileniyor…';
        window.location.reload();
      })
      .catch(function (error) { status.textContent = error.message || 'Blok eklenemedi.'; })
      .finally(function () { submit.disabled = false; });
  });

  document.querySelectorAll('[data-vox-block-delete]').forEach(function (button) {
    button.addEventListener('click', function () {
      if (!window.confirm('Bu bloğu silmek istediğinize emin misiniz?')) return;
      var data = new FormData();
      data.set('action', 'delete');
      data.set('pageKey', pageKey);
      data.set('blockId', button.dataset.voxBlockDelete);
      data.set('tokenCSRF', token);
      button.disabled = true;
      fetch(endpoint, { method: 'POST', body: data, credentials: 'same-origin' })
        .then(function (response) { return response.json(); })
        .then(function (result) {
          if (Number(result.status) !== 0) throw new Error(result.message || 'Blok silinemedi.');
          window.location.reload();
        })
        .catch(function (error) { window.alert(error.message || 'Blok silinemedi.'); button.disabled = false; });
    });
  });

  updateFields();
}());
