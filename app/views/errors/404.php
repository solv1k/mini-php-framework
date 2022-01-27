<?php template()->header() ?>

<div class="row">
    <div class="text-center">
        <h1>Ошибка 404.</h1>
        <h2>Упс! Кажется что-то не нашлось...</h2>

        <?php template()->success() ?>
        <?php template()->errors() ?>
    </div>
</div>

<?php template()->footer() ?>