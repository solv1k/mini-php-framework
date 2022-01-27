<?php if (!empty($js)) : ?>
    <?php foreach ($js as $script) : ?>
        <?php echo template()->scriptTag($script) ?>
    <?php endforeach ?>
<?php endif ?>