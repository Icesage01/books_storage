<?php

/** @var yii\web\View $this */
/** @var \src\Domain\Author\AuthorModel[] $authorList */
/** @var array $paginationInfo */
/** @var string $search */
/** @var string $active */

use yii\bootstrap5\Html;
use src\Presentation\View\widgets\PaginationWidget;

$this->title = 'Авторы';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="author-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Добавить автора', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Поиск</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?= Html::encode($search) ?>" placeholder="Имя, фамилия или отчество">
                </div>
                <div class="col-md-3">
                    <label for="active" class="form-label">Статус</label>
                    <select class="form-select" id="active" name="active">
                        <option value="">Все авторы</option>
                        <option value="1" <?= $active === '1' ? 'selected' : '' ?>>Активные</option>
                        <option value="0" <?= $active === '0' ? 'selected' : '' ?>>Неактивные</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="d-flex gap-2">
                        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Сброс', ['index'], ['class' => 'btn btn-secondary']) ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($search) || $active !== ''): ?>
        <div class="alert alert-info">
            Найдено <?= $paginationInfo['totalCount'] ?> авторов
            <?php if (!empty($search)): ?>
                по запросу "<?= Html::encode($search) ?>"
            <?php endif; ?>
            <?php if ($active === '1'): ?>
                (только активные)
            <?php elseif ($active === '0'): ?>
                (только неактивные)
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($authorList as $author): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <?= $this->render('_author_card', ['author' => $author]) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($authorList)): ?>
        <div class="alert alert-info">
            Авторы не найдены. <?= Html::a('Добавить первого автора', ['create']) ?>
        </div>
    <?php endif; ?>

    <?php if ($paginationInfo['totalPages'] > 1): ?>
        <div class="mt-4">
            <?= PaginationWidget::widget([
                'paginationInfo' => $paginationInfo,
                'route' => 'author/index',
                'params' => array_filter([
                    'search' => $search,
                    'active' => $active,
                ]),
            ]) ?>
        </div>
    <?php endif; ?>
</div>
