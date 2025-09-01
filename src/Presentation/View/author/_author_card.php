<?php

/** @var yii\web\View $this */
/** @var \src\Domain\Author\AuthorModel $author */

use yii\bootstrap5\Html;

?>

<div class="author-card">
    <h5 class="card-title"><?= Html::encode($author->getFullName()) ?></h5>
    
    <?php if (!is_null($author->middleName) && !empty($author->middleName)): ?>
        <p class="card-text text-muted">
            <small>Отчество: <?= Html::encode($author->middleName) ?></small>
        </p>
    <?php endif; ?>
    
    <p class="card-text">
        <strong>Статус:</strong> 
        <?php if ($author->isActive()): ?>
            <span class="badge bg-success">Активен</span>
        <?php else: ?>
            <span class="badge bg-secondary">Неактивен</span>
        <?php endif; ?>
    </p>
    
    <p class="card-text">
        <strong>Книг:</strong> <?= $author->getBookList()->count() ?>
    </p>
    
    <div class="mt-auto">
        <?= Html::a('Просмотр', ['view', 'id' => $author->id], ['class' => 'btn btn-primary btn-sm']) ?>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Редактировать', ['update', 'id' => $author->id], ['class' => 'btn btn-warning btn-sm']) ?>
        <?php endif; ?>
    </div>
</div>
