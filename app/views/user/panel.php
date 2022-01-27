<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo url('/') ?>"><?php echo config('app_name') ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <div class="d-flex">
                <?php if (!empty(user())) : ?>
                <div class="nav-item dropdown">
                    <a class="btn dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo user()->email ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><h6 class="dropdown-header text-success"><?php echo user()->login ?></h6></li>
                        <li><a class="dropdown-item" href="<?php echo url('/user/orders') ?>">Мои заказы</a></li>
                        <li><a class="dropdown-item" href="<?php echo url('/user/orders/create') ?>">Создать новый заказ</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="<?php echo url('/user/settings') ?>">Настройки</a></li>
                        <li><a class="dropdown-item" href="<?php echo url('/logout') ?>">Выход</a></li>
                    </ul>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</nav>