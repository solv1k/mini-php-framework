<?php template()->header() ?>

<div class="row">
    <div class="col-4">
        <h1>Регистрация</h1>

        <?php template()->success() ?>
        <?php template()->errors() ?>

        <form action="register/do" autocomplete="off" method="POST" class="form auth-form">
            <input autocomplete="false" name="hidden" type="text" style="display:none;">

            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input class="form-control" type="email" name="email" placeholder="user@site.com" value="<?php echo $old['email'] ?? '' ?>">
            </div>

            <div class="mb-3">
                <label for="login" class="form-label">Логин:</label>
                <input class="form-control" type="text" name="login" placeholder="Login" value="<?php echo $old['login'] ?? '' ?>">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Пароль:</label>
                <input class="form-control" type="password" name="password" placeholder="Password" value="">
            </div>

            <button class="btn btn-success" type="submit">Регистрация</button>

            <a class="btn btn-link" href="<?php echo url('/login') ?>">У меня есть аккаунт</a>
        </form>
    </div>
</div>

<?php template()->footer() ?>