<?php
if (empty($voxBlocks) || !is_array($voxBlocks)) {
    return;
}

$safeText = static fn ($value): string => htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
?>
<section class="vox-custom-blocks" aria-label="Ek sayfa içerikleri">
    <div class="container">
        <?php foreach ($voxBlocks as $block):
            if (!is_array($block) || empty($block['id']) || empty($block['type'])) {
                continue;
            }
            $type = (string)$block['type'];
        ?>
        <article class="vox-content-block vox-block-<?php echo $safeText($type); ?>" data-vox-block-id="<?php echo $safeText($block['id']); ?>">
            <?php if (!empty($voxAdminLoggedIn)): ?><div class="vox-block-admin-actions"><button class="vox-block-edit" type="button" data-vox-block-edit="<?php echo $safeText($block['id']); ?>" data-block-type="<?php echo $safeText($type); ?>" data-block-title="<?php echo $safeText($block['title'] ?? ''); ?>" data-block-text="<?php echo $safeText($block['text'] ?? ''); ?>" data-block-image-url="<?php echo $safeText($block['imageUrl'] ?? ''); ?>" data-block-button-label="<?php echo $safeText($block['buttonLabel'] ?? ''); ?>" data-block-button-url="<?php echo $safeText($block['buttonUrl'] ?? ''); ?>">Bloğu düzenle</button><button class="vox-block-delete" type="button" data-vox-block-delete="<?php echo $safeText($block['id']); ?>">Bloğu sil</button></div><?php endif; ?>
            <?php if ($type === 'heading'): ?>
                <h2><?php echo $safeText($block['title'] ?? ''); ?></h2>
            <?php elseif ($type === 'text'): ?>
                <p><?php echo nl2br($safeText($block['text'] ?? '')); ?></p>
            <?php elseif ($type === 'image' && !empty($block['imageUrl'])): ?>
                <figure><img src="<?php echo $safeText($block['imageUrl']); ?>" alt="<?php echo $safeText($block['title'] ?? ''); ?>" loading="lazy"><?php if (!empty($block['title'])): ?><figcaption><?php echo $safeText($block['title']); ?></figcaption><?php endif; ?></figure>
            <?php elseif ($type === 'cta'): ?>
                <div><?php if (!empty($block['title'])): ?><h2><?php echo $safeText($block['title']); ?></h2><?php endif; ?><?php if (!empty($block['text'])): ?><p><?php echo nl2br($safeText($block['text'])); ?></p><?php endif; ?></div>
                <?php if (!empty($block['buttonLabel']) && !empty($block['buttonUrl'])): ?><a class="button" href="<?php echo $safeText($block['buttonUrl']); ?>"><?php echo $safeText($block['buttonLabel']); ?> <b class="arrow">→</b></a><?php endif; ?>
            <?php endif; ?>
        </article>
        <?php endforeach; ?>
    </div>
</section>
