<?php

/** @var yii\web\View $this */
/** @var \src\Domain\Book\BookModel $book */
/** @var \src\Domain\Author\AuthorModel[] $authorList */
/** @var array $selectedAuthorIds */
/** @var \src\Validation\BookValidation $validation */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Редактировать книгу: ' . Html::encode($book->title);
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $book->title, 'url' => ['view', 'id' => $book->id]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>

<div class="book-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'book-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-2 col-form-label'],
            'inputOptions' => ['class' => 'col-lg-10 form-control'],
            'errorOptions' => ['class' => 'col-lg-10 invalid-feedback'],
        ],
    ]); ?>

    <?= $form->field($validation, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($validation, 'publicationYear')->textInput(['type' => 'number', 'min' => 1800, 'max' => date('Y')]) ?>
    <?= $form->field($validation, 'isbn')->textInput(['maxlength' => true]) ?>
    <?= $form->field($validation, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($validation, 'coverImage')->textInput(['maxlength' => true]) ?>

    <div class="form-group row">
        <label class="col-lg-2 col-form-label">Авторы</label>
        <div class="col-lg-10">
            <?php foreach ($authorList as $author): ?>
                <div class="form-check">
                    <?= Html::checkbox(
                        'authorIds[]',
                        in_array($author->id, $selectedAuthorIds),
                        [
                            'value' => $author->id,
                            'class' => 'form-check-input',
                            'id' => 'author-' . $author->id,
                        ]
                    ) ?>
                    <label class="form-check-label" for="author-<?= $author->id ?>">
                        <?= Html::encode($author->getFullName()) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-lg-10 offset-lg-2">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Отмена', ['view', 'id' => $book->id], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
