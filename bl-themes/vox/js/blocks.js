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
  var editingBlockId = '';
  var blockDialogTitle = dialog.querySelector('[data-vox-block-dialog-title]');
  var blockSubmitButton = dialog.querySelector('[data-vox-block-submit]');
  var blockImageFile = dialog.querySelector('[data-vox-block-image-file]');

  var inlineEditButton = document.querySelector('[data-vox-inline-edit]');
  var inlineSaveButton = document.querySelector('[data-vox-inline-save]');
  var inlineCancelButton = document.querySelector('[data-vox-inline-cancel]');
  var editableFields = Array.prototype.slice.call(document.querySelectorAll('[data-vox-edit-field]'));
  var editableImages = Array.prototype.slice.call(document.querySelectorAll('[data-vox-edit-image]'));
  var originalValues = {};
  var originalImages = {};
  var selectedImageElement = null;
  var imageDialog = document.querySelector('[data-vox-image-dialog]');
  var imageForm = imageDialog ? imageDialog.querySelector('[data-vox-image-form]') : null;
  var imagePreview = imageDialog ? imageDialog.querySelector('[data-vox-image-preview]') : null;
  var imageFile = imageDialog ? imageDialog.querySelector('[data-vox-image-file]') : null;
  var imageUrl = imageDialog ? imageDialog.querySelector('[data-vox-image-url]') : null;
  var imageStatus = imageDialog ? imageDialog.querySelector('[data-vox-image-status]') : null;

  function setImagePreview(element, url) {
    element.dataset.voxImageUrl = url;
    element.style.backgroundImage = 'url("' + url.replace(/["\\]/g, '\\$&') + '")';
  }

  function openImageEditor(element) {
    if (!imageDialog) return;
    selectedImageElement = element;
    imageUrl.value = element.dataset.voxImageUrl || '';
    imageFile.value = '';
    imagePreview.src = imageUrl.value;
    imageStatus.textContent = '';
    imageStatus.className = 'vox-block-form-status';
    imageDialog.showModal();
  }

  function setInlineMode(active) {
    document.body.classList.toggle('vox-inline-editing', active);
    editableFields.forEach(function (field) {
      field.contentEditable = active ? 'true' : 'false';
      if (active) originalValues[field.dataset.voxEditField] = field.innerText.trim();
    });
    editableImages.forEach(function (element) {
      var key = element.dataset.voxEditImage;
      if (active) {
        originalImages[key] = element.dataset.voxImageUrl || '';
        if (!element.querySelector('.vox-image-edit-chip')) {
          var chip = document.createElement('button');
          chip.type = 'button';
          chip.className = 'vox-image-edit-chip';
          chip.textContent = 'Görseli değiştir';
          chip.addEventListener('click', function (event) { event.preventDefault(); event.stopPropagation(); openImageEditor(element); });
          element.appendChild(chip);
        }
      } else {
        var chip = element.querySelector('.vox-image-edit-chip');
        if (chip) chip.remove();
      }
    });
    if (inlineEditButton) inlineEditButton.hidden = active;
    if (inlineSaveButton) inlineSaveButton.hidden = !active;
    if (inlineCancelButton) inlineCancelButton.hidden = !active;
  }

  if (inlineEditButton && editableFields.length) {
    inlineEditButton.addEventListener('click', function () { setInlineMode(true); });
    inlineCancelButton.addEventListener('click', function () {
      editableFields.forEach(function (field) { field.innerText = originalValues[field.dataset.voxEditField] || ''; });
      editableImages.forEach(function (element) { setImagePreview(element, originalImages[element.dataset.voxEditImage] || ''); });
      setInlineMode(false);
    });
    inlineSaveButton.addEventListener('click', function () {
      var fields = {};
      editableFields.forEach(function (field) { fields[field.dataset.voxEditField] = field.innerText.trim(); });
      editableImages.forEach(function (element) { fields[element.dataset.voxEditImage] = element.dataset.voxImageUrl || ''; });
      var data = new FormData();
      data.set('action', 'save-home');
      data.set('pageKey', 'home');
      data.set('fields', JSON.stringify(fields));
      data.set('tokenCSRF', token);
      inlineSaveButton.disabled = true;
      inlineSaveButton.textContent = 'Kaydediliyor…';
      fetch(endpoint, { method: 'POST', body: data, credentials: 'same-origin' })
        .then(function (response) { return response.json(); })
        .then(function (result) {
          if (Number(result.status) !== 0) throw new Error(result.message || 'Ana sayfa kaydedilemedi.');
          window.location.reload();
        })
        .catch(function (error) {
          window.alert(error.message || 'Ana sayfa kaydedilemedi.');
          inlineSaveButton.disabled = false;
          inlineSaveButton.textContent = 'Değişiklikleri kaydet';
        });
    });
  }

  if (imageDialog && imageForm) {
    imageDialog.querySelectorAll('[data-vox-image-close]').forEach(function (button) {
      button.addEventListener('click', function () { imageDialog.close(); });
    });
    imageUrl.addEventListener('input', function () { if (imageUrl.value) imagePreview.src = imageUrl.value; });
    imageFile.addEventListener('change', function () {
      if (imageFile.files && imageFile.files[0]) imagePreview.src = URL.createObjectURL(imageFile.files[0]);
    });
    imageForm.addEventListener('submit', function (event) {
      event.preventDefault();
      if (!selectedImageElement) return;
      var submit = imageForm.querySelector('.vox-block-save');
      var chosenFile = imageFile.files && imageFile.files[0];
      var typedUrl = imageUrl.value.trim();
      var finish = function (url) {
        setImagePreview(selectedImageElement, url);
        imageDialog.close();
      };

      if (!chosenFile) {
        if (!/^(?:https?:\/\/|\/)/i.test(typedUrl)) {
          imageStatus.textContent = 'Geçerli bir görsel adresi girin veya dosya seçin.';
          return;
        }
        finish(typedUrl);
        return;
      }

      var uploadData = new FormData();
      uploadData.set('image', chosenFile);
      uploadData.set('tokenCSRF', token);
      submit.disabled = true;
      imageStatus.textContent = 'Görsel yükleniyor…';
      fetch(imageDialog.dataset.uploadEndpoint, { method: 'POST', body: uploadData, credentials: 'same-origin' })
        .then(function (response) { return response.json(); })
        .then(function (result) {
          if (Number(result.status) !== 0 || !result.url) throw new Error(result.message || 'Görsel yüklenemedi.');
          finish(result.url);
        })
        .catch(function (error) { imageStatus.textContent = error.message || 'Görsel yüklenemedi.'; })
        .finally(function () { submit.disabled = false; });
    });
  }

  function updateFields() {
    var type = typeSelect.value;
    dialog.querySelector('[data-field="title"]').hidden = type === 'text';
    dialog.querySelector('[data-field="text"]').hidden = type === 'heading' || type === 'image';
    dialog.querySelector('[data-field="image"]').hidden = type !== 'image';
    dialog.querySelector('[data-field="button"]').hidden = type !== 'cta';
  }

  function openBlockEditor(values) {
    form.reset();
    editingBlockId = values ? values.id : '';
    blockDialogTitle.textContent = values ? 'Bloğu düzenle' : 'Yeni blok ekle';
    blockSubmitButton.textContent = values ? 'Değişiklikleri kaydet' : 'Bloğu ekle';
    if (values) {
      typeSelect.value = values.type || 'text';
      form.elements.title.value = values.title || '';
      form.elements.text.value = values.text || '';
      form.elements.imageUrl.value = values.imageUrl || '';
      form.elements.buttonLabel.value = values.buttonLabel || '';
      form.elements.buttonUrl.value = values.buttonUrl || '';
    }
    status.textContent = '';
    status.className = 'vox-block-form-status';
    updateFields();
    dialog.showModal();
  }

  openButton.addEventListener('click', function () {
    openBlockEditor(null);
  });

  document.querySelectorAll('[data-vox-block-edit]').forEach(function (button) {
    button.addEventListener('click', function () {
      openBlockEditor({
        id: button.dataset.voxBlockEdit,
        type: button.dataset.blockType,
        title: button.dataset.blockTitle,
        text: button.dataset.blockText,
        imageUrl: button.dataset.blockImageUrl,
        buttonLabel: button.dataset.blockButtonLabel,
        buttonUrl: button.dataset.blockButtonUrl
      });
    });
  });

  dialog.querySelectorAll('[data-vox-block-close]').forEach(function (button) {
    button.addEventListener('click', function () { dialog.close(); });
  });

  typeSelect.addEventListener('change', updateFields);

  form.addEventListener('submit', function (event) {
    event.preventDefault();
    var submit = form.querySelector('.vox-block-save');
    submit.disabled = true;
    status.textContent = editingBlockId ? 'Blok güncelleniyor…' : 'Blok kaydediliyor…';

    var saveBlock = function () {
      var data = new FormData(form);
      data.delete('blockImage');
      data.set('action', editingBlockId ? 'update' : 'add');
      data.set('pageKey', pageKey);
      data.set('blockId', editingBlockId);
      data.set('tokenCSRF', token);
      return fetch(endpoint, { method: 'POST', body: data, credentials: 'same-origin' });
    };

    var uploadPromise = Promise.resolve(null);
    if (typeSelect.value === 'image' && blockImageFile.files && blockImageFile.files[0]) {
      var uploadData = new FormData();
      uploadData.set('image', blockImageFile.files[0]);
      uploadData.set('tokenCSRF', token);
      status.textContent = 'Görsel yükleniyor…';
      uploadPromise = fetch(dialog.dataset.uploadEndpoint, { method: 'POST', body: uploadData, credentials: 'same-origin' })
        .then(function (response) { return response.json(); })
        .then(function (result) {
          if (Number(result.status) !== 0 || !result.url) throw new Error(result.message || 'Görsel yüklenemedi.');
          form.elements.imageUrl.value = result.url;
        });
    }

    uploadPromise.then(saveBlock)
      .then(function (response) { return response.json(); })
      .then(function (result) {
        if (Number(result.status) !== 0) throw new Error(result.message || 'Blok kaydedilemedi.');
        status.className = 'vox-block-form-status success';
        status.textContent = (editingBlockId ? 'Blok güncellendi.' : 'Blok eklendi.') + ' Sayfa yenileniyor…';
        window.location.reload();
      })
      .catch(function (error) { status.textContent = error.message || 'Blok kaydedilemedi.'; })
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
