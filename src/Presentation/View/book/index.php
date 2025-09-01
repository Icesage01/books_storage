<?php

/** @var yii\web\View $this */
/** @var \src\Domain\Book\BookModel[] $bookList */
/** @var array $paginationInfo */
/** @var string $search */
/** @var string $year */

use yii\bootstrap5\Html;
use src\Presentation\View\widgets\PaginationWidget;

$this->title = 'Каталог книг';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="book-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?php if (!Yii::$app->user->isGuest): ?>
            <?= Html::a('Добавить книгу', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Поиск</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?= Html::encode($search) ?>" placeholder="Название или описание">
                </div>
                <div class="col-md-3">
                    <label for="year" class="form-label">Год издания</label>
                    <select class="form-select" id="year" name="year">
                        <option value="">Все годы</option>
                        <?php for ($y = date('Y'); $y >= 1900; $y--): ?>
                            <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>>
                                <?= $y ?>
                            </option>
                        <?php endfor; ?>
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

    <?php if (!empty($search) || !empty($year)): ?>
        <div class="alert alert-info">
            Найдено <?= $paginationInfo['totalCount'] ?> книг
            <?php if (!empty($search)): ?>
                по запросу "<?= Html::encode($search) ?>"
            <?php endif; ?>
            <?php if (!empty($year)): ?>
                за <?= $year ?> год
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php foreach ($bookList as $book): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <?= $this->render('_book_card', ['book' => $book]) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($bookList)): ?>
        <div class="alert alert-info">
            Книги не найдены. <?= Html::a('Добавить первую книгу', ['create']) ?>
        </div>
    <?php endif; ?>

    <?php if ($paginationInfo['totalPages'] > 1): ?>
        <div class="mt-4">
            <?= PaginationWidget::widget([
                'paginationInfo' => $paginationInfo,
                'route' => 'book/index',
                'params' => array_filter([
                    'search' => $search,
                    'year' => $year,
                ]),
            ]) ?>
        </div>
    <?php endif; ?>
</div>
