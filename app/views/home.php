<?php template()->header(); ?>


<div class="row">
    <div class="col-lg-4">
        <h1>Главная страница</h1>

        <?php template()->success() ?>
        <?php template()->errors() ?>

        <ul class="links">
            <?php if (!empty(user())) : ?>
                <li><a href="<?php echo url('/user/orders') ?>">Заказы</a></li>
                <li><a href="<?php echo url('/user/orders/create') ?>">Создать новый заказ</a></li>
                <li><a href="<?php echo url('/user/settings') ?>">Настройки</a></li>
                <li><a href="<?php echo url('/logout') ?>">Выход</a></li>
            <?php else : ?>
                <li><a href="<?php echo url('/login'); ?>">Авторизация</a></li>
                <li><a href="<?php echo url('/register'); ?>">Регистрация</a></li>
            <?php endif ?>
        </ul>     
    </div>
    <div class="col-lg-4 offset-lg-1">
        <div class="card orders-card mb-3">
            <div class="card-header">Статистика по заказам</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    Пользователи сделавшие более 2-х заказов: <br>
                    <?php echo view('inc.userlist', ['users' => $usersWithManyOrders ?? []]) ?>
                </li>
                <li class="list-group-item">
                    Пользователи не сделавшие ни одного заказа: <br>
                    <?php echo view('inc.userlist', ['users' => $usersWithoutOrders ?? []]) ?>
                </li>
            </ul>
        </div>   
    </div>
</div>

<?php template()->footer(); ?>