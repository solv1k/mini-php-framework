<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo config('app_name'); ?> | <?php echo $title ?? 'Добро пожаловать'; ?></title>
    <?php template()->styles() ?>
</head>
<body>
    <div class="container">
        <?php view('user.panel', ['user' => $user ?? []]) ?>
        
        <div class="content pt-4">