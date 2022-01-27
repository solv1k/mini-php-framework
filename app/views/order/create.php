<?php template()->header() ?>

<div class="row">
    <div class="col-4">
        <h1>Создание заказа</h1>

        <?php template()->success() ?>
        <?php template()->errors() ?>

        <form action="<?php echo url('/user/orders/store') ?>" method="POST" class="form">
            <div class="mb-3">
                <label for="price" class="form-label">Price:</label>
                <input class="form-control" type="number" name="price" placeholder="price" value="<?php echo $old->price ?? '' ?>">
            </div>

            <button class="btn btn-success" type="submit">Создать заказ</button>
            <a class="btn btn-link" href="<?php echo url(request()->referer()) ?>">Назад</a>
        </form>
    </div>
</div>

<?php template()->footer() ?>