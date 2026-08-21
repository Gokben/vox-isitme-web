<?php defined('BLUDIT') or die('Bludit CMS.');

echo Bootstrap::pageTitle(array('title' => 'Medya', 'icon' => 'picture-o'));
?>

<div class="vox-media-upload-card">
    <div>
        <h3>Görsel yükle</h3>
        <p>JPG, PNG, WebP veya GIF · Dosya başına en fazla 8 MB · Tek seferde en fazla 20 görsel</p>
    </div>
    <label class="vox-media-file-picker">
        <span class="fa fa-cloud-upload"></span>
        <strong>Görselleri seçin</strong>
        <small data-vox-media-selection>Henüz dosya seçilmedi</small>
        <input type="file" accept="image/jpeg,image/png,image/webp,image/gif" multiple data-vox-media-files>
    </label>
    <button class="btn btn-primary" type="button" data-vox-media-upload disabled><span class="fa fa-upload mr-2"></span>Yükle</button>
    <p class="vox-media-status" data-vox-media-status aria-live="polite"></p>
</div>

<div class="vox-media-toolbar">
    <div><strong>Medya klasörü</strong><span data-vox-media-count>0 görsel</span></div>
    <button class="btn btn-light" type="button" data-vox-media-refresh><span class="fa fa-refresh mr-2"></span>Yenile</button>
</div>

<div class="vox-media-grid" data-vox-media-grid></div>
<div class="vox-media-empty" data-vox-media-empty hidden>
    <span class="fa fa-picture-o"></span>
    <h3>Henüz görsel yüklenmedi</h3>
    <p>Yüklediğiniz görseller burada listelenecek.</p>
</div>

<script>
(function () {
    'use strict';

    var token = <?php echo json_encode($security->getTokenCSRF()); ?>;
    var adminRoot = <?php echo json_encode(HTML_PATH_ADMIN_ROOT); ?>;
    var filesInput = document.querySelector('[data-vox-media-files]');
    var uploadButton = document.querySelector('[data-vox-media-upload]');
    var refreshButton = document.querySelector('[data-vox-media-refresh]');
    var selection = document.querySelector('[data-vox-media-selection]');
    var status = document.querySelector('[data-vox-media-status]');
    var grid = document.querySelector('[data-vox-media-grid]');
    var empty = document.querySelector('[data-vox-media-empty]');
    var count = document.querySelector('[data-vox-media-count]');

    function escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, function (character) {
            return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[character];
        });
    }

    function formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function post(endpoint, data) {
        data.set('tokenCSRF', token);
        return fetch(adminRoot + 'ajax/' + endpoint, {method: 'POST', body: data, credentials: 'same-origin'})
            .then(function (response) { return response.json(); });
    }

    function loadMedia() {
        refreshButton.disabled = true;
        post('vox-media-list', new FormData())
            .then(function (result) {
                if (Number(result.status) !== 0) throw new Error(result.message || 'Medya listesi alınamadı.');
                renderMedia(result.files || []);
            })
            .catch(function (error) { status.textContent = error.message || 'Medya listesi alınamadı.'; })
            .finally(function () { refreshButton.disabled = false; });
    }

    function renderMedia(files) {
        count.textContent = files.length + ' görsel';
        empty.hidden = files.length !== 0;
        grid.innerHTML = files.map(function (file) {
            return '<article class="vox-media-item" data-filename="' + escapeHtml(file.filename) + '">' +
                '<a href="' + escapeHtml(file.url) + '" target="_blank" rel="noopener"><img src="' + escapeHtml(file.url) + '" alt="' + escapeHtml(file.filename) + '" loading="lazy"></a>' +
                '<div class="vox-media-item-info"><strong title="' + escapeHtml(file.filename) + '">' + escapeHtml(file.filename) + '</strong><small>' + formatSize(Number(file.size) || 0) + '</small></div>' +
                '<div class="vox-media-item-actions"><button type="button" class="btn btn-sm btn-light" data-copy-url="' + escapeHtml(file.url) + '"><span class="fa fa-copy"></span> Bağlantıyı kopyala</button><button type="button" class="btn btn-sm btn-danger" data-delete-file="' + escapeHtml(file.filename) + '"><span class="fa fa-trash"></span></button></div>' +
                '</article>';
        }).join('');
    }

    filesInput.addEventListener('change', function () {
        var total = filesInput.files ? filesInput.files.length : 0;
        selection.textContent = total ? total + ' dosya seçildi' : 'Henüz dosya seçilmedi';
        uploadButton.disabled = total === 0;
    });

    uploadButton.addEventListener('click', function () {
        if (!filesInput.files || !filesInput.files.length) return;
        var data = new FormData();
        Array.prototype.slice.call(filesInput.files, 0, 20).forEach(function (file) { data.append('images[]', file); });
        uploadButton.disabled = true;
        status.textContent = 'Görseller yükleniyor…';
        post('vox-media-upload', data)
            .then(function (result) {
                if (Number(result.status) !== 0) throw new Error(result.message || 'Görseller yüklenemedi.');
                status.textContent = result.message;
                filesInput.value = '';
                selection.textContent = 'Henüz dosya seçilmedi';
                loadMedia();
            })
            .catch(function (error) { status.textContent = error.message || 'Görseller yüklenemedi.'; uploadButton.disabled = false; });
    });

    grid.addEventListener('click', function (event) {
        var copyButton = event.target.closest('[data-copy-url]');
        if (copyButton) {
            navigator.clipboard.writeText(copyButton.dataset.copyUrl).then(function () {
                status.textContent = 'Görsel bağlantısı kopyalandı.';
            }).catch(function () { status.textContent = 'Bağlantı kopyalanamadı.'; });
            return;
        }
        var deleteButton = event.target.closest('[data-delete-file]');
        if (!deleteButton) return;
        if (!window.confirm('Bu görseli kalıcı olarak silmek istediğinize emin misiniz? Sitede kullanılıyorsa artık görünmez.')) return;
        deleteButton.disabled = true;
        var data = new FormData();
        data.set('filename', deleteButton.dataset.deleteFile);
        post('vox-media-delete', data)
            .then(function (result) {
                if (Number(result.status) !== 0) throw new Error(result.message || 'Görsel silinemedi.');
                status.textContent = 'Görsel silindi.';
                loadMedia();
            })
            .catch(function (error) { status.textContent = error.message || 'Görsel silinemedi.'; deleteButton.disabled = false; });
    });

    refreshButton.addEventListener('click', loadMedia);
    loadMedia();
}());
</script>
