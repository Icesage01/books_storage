<?php

/** @var yii\web\View $this */
/** @var Exception $exception */

use yii\bootstrap5\Html;

$this->title = $exception->getMessage();
$this->params['breadcrumbs'][] = 'Ошибка';
?>

<div class="site-error">
    <div class="alert alert-danger">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <div class="alert alert-info">
        <p>
            Произошла ошибка при обработке вашего запроса.
        </p>
        <p>
            Пожалуйста, свяжитесь с администратором, если проблема повторяется.
        </p>
    </div>

    <div class="text-center">
        <?= Html::a('Вернуться на главную', ['/site/index'], ['class' => 'btn btn-primary']) ?>
    </div>
</div>
