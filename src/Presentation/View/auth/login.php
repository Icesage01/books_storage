<?php

/** @var yii\web\View $this */

use yii\bootstrap5\Html;

$this->title = 'Вход в систему';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="auth-login">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h1 class="h3 mb-0"><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="card-body">
                    <?= Html::beginForm(['auth/login'], 'post', [
                        'id' => 'login-form',
                        'class' => 'needs-validation',
                        'novalidate' => true,
                    ]); ?>

                    <div class="mb-3">
                        <label for="username" class="form-label">Имя пользователя</label>
                        <input type="text" 
                               class="form-control" 
                               id="username" 
                               name="username" 
                               placeholder="Имя пользователя" 
                               required 
                               autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" 
                               class="form-control" 
                               id="password" 
                               name="password" 
                               placeholder="Пароль" 
                               required>
                    </div>

                    <div class="mb-3">
                        <?= Html::submitButton('Войти', [
                            'class' => 'btn btn-primary w-100',
                            'name' => 'login-button',
                        ]) ?>
                    </div>

                    <?= Html::endForm(); ?>

                    <div class="text-center">
                        <small class="text-muted">
                            Для демонстрации используйте: admin / admin
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
