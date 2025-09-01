<?php

/** @var yii\web\View $this */
/** @var \src\Domain\Author\AuthorModel $author */

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

$this->title = $author->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="author-view">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?php if (!Yii::$app->user->isGuest): ?>
                <?= Html::a('Редактировать', ['update', 'id' => $author->id], ['class' => 'btn btn-warning']) ?>
                <?= Html::beginForm(['delete', 'id' => $author->id], 'post', [
                    'style' => 'display: inline;',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить этого автора?',
                    ],
                ]) ?>
                    <?= Html::submitButton('Удалить', [
                        'class' => 'btn btn-danger',
                        'onclick' => 'return confirm("Вы уверены, что хотите удалить этого автора?");',
                    ]) ?>
                <?= Html::endForm() ?>
            <?php endif; ?>
            <?= Html::a('Назад к списку', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= DetailView::widget([
                'model' => $author,
                'attributes' => [
                    'id',
                    'firstName:ntext:Имя',
                    'lastName:ntext:Фамилия',
                    'middleName:ntext:Отчество',
                    'createdAt:datetime:Дата создания',
                    'updatedAt:datetime:Дата обновления',
                ],
            ]) ?>
        </div>
        
        <div class="col-md-6">
            <h3>Статистика</h3>
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Статус
                    <?php if ($author->isActive()): ?>
                        <span class="badge bg-success rounded-pill">Активен</span>
                    <?php else: ?>
                        <span class="badge bg-secondary rounded-pill">Неактивен</span>
                    <?php endif; ?>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Количество книг
                    <span class="badge bg-primary rounded-pill"><?= $author->getBookList()->count() ?></span>
                </li>
            </ul>
        </div>
    </div>

    <div class="mt-4">
        <h3>Книги автора</h3>
        <?php $bookList = $author->getBookList()->with('book')->all(); ?>
        
        <?php if (!empty($bookList)): ?>
            <div class="row">
                <?php foreach ($bookList as $bookAuthor): ?>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= Html::encode($bookAuthor->book->title) ?></h5>
                                <p class="card-text">
                                    <strong>Год:</strong> <?= $bookAuthor->book->publicationYear ?><br>
                                    <strong>ISBN:</strong> <?= Html::encode($bookAuthor->book->isbn) ?>
                                </p>
                                <?= Html::a('Просмотр', ['/book/view', 'id' => $bookAuthor->book->id], ['class' => 'btn btn-primary btn-sm']) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                У этого автора пока нет книг.
            </div>
        <?php endif; ?>
    </div>
</div>
