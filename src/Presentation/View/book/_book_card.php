<?php

/** @var \src\Domain\Book\BookModel $book */

use yii\bootstrap5\Html;

$authorNames = [];
foreach ($book->authorList as $bookAuthor) {
    if ($bookAuthor->author) {
        $authorNames[] = $bookAuthor->author->getFullName();
    }
}
?>

<h5 class="card-title"><?= Html::encode($book->title) ?></h5>
<p class="card-text">
    <strong>Авторы:</strong> <?= Html::encode(implode(', ', $authorNames)) ?><br>
    <strong>Год:</strong> <?= Html::encode($book->publicationYear) ?><br>
    <strong>ISBN:</strong> <?= Html::encode($book->isbn) ?>
</p>
<?php if (!empty($book->description)): ?>
    <p class="card-text"><?= Html::encode(mb_substr($book->description, 0, 100)) ?>...</p>
<?php endif; ?>
<div class="mt-auto">
    <?= Html::a('Подробнее', ['view', 'id' => $book->id], ['class' => 'btn btn-primary btn-sm']) ?>
    <?php if (!Yii::$app->user->isGuest): ?>
        <?= Html::a('Редактировать', ['update', 'id' => $book->id], ['class' => 'btn btn-warning btn-sm']) ?>
        <?= Html::beginForm(['delete', 'id' => $book->id], 'post', [
            'style' => 'display: inline;',
        ]) ?>
            <?= Html::submitButton('Удалить', [
                'class' => 'btn btn-danger btn-sm',
                'onclick' => 'return confirm("Вы уверены, что хотите удалить эту книгу?");',
            ]) ?>
        <?= Html::endForm() ?>
    <?php endif; ?>
</div>
