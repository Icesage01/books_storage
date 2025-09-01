<?php

/** @var yii\web\View $this */
/** @var \src\Domain\Author\AuthorModel $author */
/** @var \src\Validation\AuthorValidation $validation */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Создать автора';
$this->params['breadcrumbs'][] = ['label' => 'Авторы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="author-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin([
        'id' => 'author-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n{input}\n{error}",
            'labelOptions' => ['class' => 'col-lg-2 col-form-label'],
            'inputOptions' => ['class' => 'col-lg-10 form-control'],
            'errorOptions' => ['class' => 'col-lg-10 invalid-feedback'],
        ],
    ]); ?>

    <?= $form->field($validation, 'firstName')->textInput(['maxlength' => true]) ?>
    <?= $form->field($validation, 'lastName')->textInput(['maxlength' => true]) ?>
    <?= $form->field($validation, 'middleName')->textInput(['maxlength' => true]) ?>
    <?= $form->field($validation, 'isActive')->checkbox() ?>

    <div class="form-group row">
        <div class="col-lg-10 offset-lg-2">
            <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
            <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>
