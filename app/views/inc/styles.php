<?php if (!empty($css)) : ?>
    <?php foreach ($css as $style) : ?>
        <?php echo template()->linkTag($style) ?>
    <?php endforeach ?>
<?php endif ?>