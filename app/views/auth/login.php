<?php template()->header() ?>

<div class="row">
    <div class="col-4">
        <h1>Форма входа</h1>

        <?php template()->success() ?>
        <?php template()->errors() ?>

        <form action="login/do" method="POST" class="form auth-form">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input class="form-control" type="email" name="email" placeholder="user@site.com" value="<?php echo $old['email'] ?? '' ?>">
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Пароль:</label>
                <input class="form-control" type="password" name="password" placeholder="Password" value="">
            </div>

            <button class="btn btn-success" type="submit">Войти</button>
            <a class="btn btn-link" href="<?php echo url('/register') ?>">Регистрация</a>
        </form>
    </div>
</div>

<?php template()->footer() ?>