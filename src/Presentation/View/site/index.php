<?php

/** @var yii\web\View $this */
/** @var array $bookList */
/** @var array $authorList */

use yii\bootstrap5\Html;

$this->title = 'Каталог книг';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-index">
    <div class="jumbotron text-center bg-transparent">
        <h1 class="display-4">Добро пожаловать в каталог книг!</h1>
        <p class="lead">Найдите интересные книги и подпишитесь на уведомления о новых поступлениях от любимых авторов.</p>
        <p>
            <?= Html::a('Просмотреть все книги', ['/book/index'], ['class' => 'btn btn-lg btn-success']) ?>
        </p>
    </div>

    <div class="body-content">
        <div class="row">
            <div class="col-lg-8">
                <h2>Последние поступления</h2>
                <div class="row">
                    <?php foreach ($bookList as $book): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column">
                                    <?= $this->render('../book/_book_card', ['book' => $book]) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-3">
                    <?= Html::a('Все книги', ['/book/index'], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            
            <div class="col-lg-4">
                <h2>Авторы</h2>
                <div class="list-group">
                    <?php foreach ($authorList as $author): ?>
                        <?= Html::a(
                            $author->getFullName(),
                            ['/author/view', 'id' => $author->id],
                            ['class' => 'list-group-item list-group-item-action']
                        ) ?>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-3">
                    <?= Html::a('Все авторы', ['/author/index'], ['class' => 'btn btn-outline-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
