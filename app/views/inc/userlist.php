<?php if (!empty($users)) : ?>
    <strong><?php echo implode(', ', $users) ?></strong>
<?php else : ?>
    <strong>...</strong>
<?php endif ?>