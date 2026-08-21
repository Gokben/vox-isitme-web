(function () {
  'use strict';

  var dialog = document.querySelector('[data-vox-blog-dialog]');
  if (!dialog) return;

  var form = dialog.querySelector('[data-vox-blog-form]');
  var status = dialog.querySelector('[data-vox-blog-status]');
  var imageFile = dialog.querySelector('[data-vox-blog-image-file]');
  var token = dialog.dataset.token;
  var manageButton = document.querySelector('[data-vox-blog-manage]');

  if (manageButton) {
    manageButton.addEventListener('click', function () {
      document.body.classList.add('vox-blog-manage-active');
      var firstAction = document.querySelector('.vox-blog-admin-actions');
      if (firstAction) firstAction.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
  }

  dialog.querySelectorAll('[data-vox-blog-close]').forEach(function (button) {
    button.addEventListener('click', function () { dialog.close(); });
  });

  document.querySelectorAll('[data-vox-blog-edit]').forEach(function (button) {
    button.addEventListener('click', function () {
      form.reset();
      form.elements.slug.value = button.dataset.blogSlug || '';
      form.elements.title.value = button.dataset.blogTitle || '';
      form.elements.date.value = button.dataset.blogDate || '';
      form.elements.excerpt.value = button.dataset.blogExcerpt || '';
      form.elements.content.value = button.dataset.blogContent || '';
      form.elements.alt.value = button.dataset.blogAlt || '';
      form.elements.image.value = button.dataset.blogImage || '';
      status.textContent = '';
      status.className = 'vox-block-form-status';
      dialog.showModal();
    });
  });

  form.addEventListener('submit', function (event) {
    event.preventDefault();
    var submit = form.querySelector('.vox-block-save');
    submit.disabled = true;
    status.textContent = 'Blog yazısı kaydediliyor…';

    var uploadPromise = Promise.resolve(null);
    if (imageFile.files && imageFile.files[0]) {
      var uploadData = new FormData();
      uploadData.set('image', imageFile.files[0]);
      uploadData.set('tokenCSRF', token);
      status.textContent = 'Görsel yükleniyor…';
      uploadPromise = fetch(dialog.dataset.uploadEndpoint, { method: 'POST', body: uploadData, credentials: 'same-origin' })
        .then(function (response) { return response.json(); })
        .then(function (result) {
          if (Number(result.status) !== 0 || !result.url) throw new Error(result.message || 'Görsel yüklenemedi.');
          form.elements.image.value = result.url;
        });
    }

    uploadPromise.then(function () {
      var data = new FormData(form);
      data.delete('blogImageFile');
      data.set('action', 'update');
      data.set('tokenCSRF', token);
      return fetch(dialog.dataset.endpoint, { method: 'POST', body: data, credentials: 'same-origin' });
    })
      .then(function (response) { return response.json(); })
      .then(function (result) {
        if (Number(result.status) !== 0) throw new Error(result.message || 'Blog yazısı kaydedilemedi.');
        window.location.reload();
      })
      .catch(function (error) { status.textContent = error.message || 'Blog yazısı kaydedilemedi.'; })
      .finally(function () { submit.disabled = false; });
  });

  document.querySelectorAll('[data-vox-blog-delete]').forEach(function (button) {
    button.addEventListener('click', function () {
      if (!window.confirm('Bu blog yazısını kalıcı olarak silmek istediğinize emin misiniz?')) return;
      var data = new FormData();
      data.set('action', 'delete');
      data.set('slug', button.dataset.voxBlogDelete);
      data.set('tokenCSRF', token);
      button.disabled = true;
      fetch(dialog.dataset.endpoint, { method: 'POST', body: data, credentials: 'same-origin' })
        .then(function (response) { return response.json(); })
        .then(function (result) {
          if (Number(result.status) !== 0) throw new Error(result.message || 'Blog yazısı silinemedi.');
          window.location.href = window.location.pathname;
        })
        .catch(function (error) { window.alert(error.message || 'Blog yazısı silinemedi.'); button.disabled = false; });
    });
  });
}());
