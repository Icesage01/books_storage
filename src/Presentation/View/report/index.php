<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'Отчеты';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="report-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Топ авторов</h5>
                    <p class="card-text">
                        Отчет по самым популярным авторам за выбранный год.
                    </p>
                    <?= Html::a('Просмотреть отчет', ['top-authors'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Статистика книг</h5>
                    <p class="card-text">
                        Общая статистика по книгам в каталоге.
                    </p>
                    <button class="btn btn-secondary" disabled>Скоро</button>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Активность подписок</h5>
                    <p class="card-text">
                        Отчет по активности подписок пользователей.
                    </p>
                    <button class="btn btn-secondary" disabled>Скоро</button>
                </div>
            </div>
        </div>
    </div>
</div>
