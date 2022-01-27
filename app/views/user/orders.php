<?php template()->header() ?>

<h1>Заказы</h1>

<div class="row">
    <div class="col-4">
        <?php template()->success() ?>
        <?php template()->errors() ?>

        <?php if (!empty($orders)) : ?> 
            <?php foreach ($orders as $order) : ?>
                <div class="card user-orders-card mb-3" style="width: 18rem;">
                    <div class="card-header">Заказ #<?php echo $order->id ?></div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Price: <?php echo $order->price ?></li>
                    </ul>
                </div>
            <?php endforeach ?>
        <?php else : ?>
            <div class="alert alert-info">У вас ещё нет заказов.</div>
        <?php endif ?>

        <a href="<?php echo url('/user/orders/create') ?>" class="btn btn-success">Создать новый заказ</a>
        <a href="<?php echo url('/') ?>" class="btn btn-link">На главную</a>
    </div>
</div>

<?php template()->footer() ?>