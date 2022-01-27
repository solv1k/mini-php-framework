<?php template()->header() ?>

<div class="row">
    <div class="col-4">
        <h1>Настройки</h1>

        <?php template()->success() ?>
        <?php template()->errors() ?>

        <form action="<?php echo url('/user/settings/update') ?>" method="POST" class="form">
            <div class="mb-3">
                <label for="login" class="form-label">Новый логин:</label>
                <input class="form-control" type="text" name="login" placeholder="user@site.com" value="<?php echo $old->login ?? user()->login ?>">
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Новый пароль:</label>
                <input class="form-control" type="password" name="password" placeholder="Password" value="<?php echo $old->password ?? '' ?>">
            </div>

            <button type="submit" class="btn btn-success">Сохранить изменения</button>
            <a href="<?php echo url('/') ?>" class="btn btn-link">На главную</a>
        </form>
    </div>
</div>

<?php template()->footer() ?>