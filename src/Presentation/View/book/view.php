<?php

/** @var yii\web\View $this */
/** @var \src\Domain\Book\BookModel $book */

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

$this->title = $book->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="book-view">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?php if (!Yii::$app->user->isGuest): ?>
                <?= Html::a('Редактировать', ['update', 'id' => $book->id], ['class' => 'btn btn-warning']) ?>
                <?= Html::beginForm(['delete', 'id' => $book->id], 'post', [
                    'style' => 'display: inline;',
                ]) ?>
                    <?= Html::submitButton('Удалить', [
                        'class' => 'btn btn-danger',
                        'onclick' => 'return confirm("Вы уверены, что хотите удалить эту книгу?");',
                    ]) ?>
                <?= Html::endForm() ?>
            <?php endif; ?>
            <?= Html::a('Назад к списку', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <?= DetailView::widget([
                'model' => $book,
                'attributes' => [
                    'id',
                    'title:ntext:Название',
                    'publicationYear:ntext:Год выпуска',
                    'isbn:ntext:ISBN',
                    'description:ntext:Описание',
                    'coverImage:ntext:Обложка',
                    'createdAt:datetime:Дата создания',
                    'updatedAt:datetime:Дата обновления',
                ],
            ]) ?>
        </div>
        
        <div class="col-md-4">
            <h3>Авторы</h3>
            <?php $authorList = $book->getAuthorList()->with('author')->all(); ?>
            
            <?php if (!empty($authorList)): ?>
                <ul class="list-group">
                    <?php foreach ($authorList as $bookAuthor): ?>
                        <li class="list-group-item">
                            <?= Html::a(
                                $bookAuthor->author->getFullName(),
                                ['/author/view', 'id' => $bookAuthor->author->id],
                                ['class' => 'text-decoration-none']
                            ) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="alert alert-info">
                    У этой книги пока нет авторов.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
