<?php template()->header(); ?>

<h1>Список пользователей</h1>

<?php if (!empty($success)) : ?>
    <div class="success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if (!empty($users)) : ?>
    <ul>
        <?php foreach ($users as $user) : ?>
            <li>
                <?php echo $user->login; ?> [<?php echo $user->email; ?>]
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php template()->footer(); ?>