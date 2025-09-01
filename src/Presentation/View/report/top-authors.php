<?php

/** @var yii\web\View $this */
/** @var array $topAuthors */
/** @var int $year */

use yii\bootstrap5\Html;

$this->title = 'Топ авторов';
$this->params['breadcrumbs'][] = ['label' => 'Отчеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="report-top-authors">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Назад к отчетам', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <form method="get" class="d-flex">
                <label for="year" class="form-label me-2">Год:</label>
                <select name="year" id="year" class="form-select me-2" style="width: auto;">
                    <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                        <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>>
                            <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <?= Html::submitButton('Показать', ['class' => 'btn btn-primary']) ?>
            </form>
        </div>
    </div>

    <?php if (!empty($topAuthors)): ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Место</th>
                        <th>Автор</th>
                        <th>Количество книг</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topAuthors as $index => $author): ?>
                        <tr>
                            <td>
                                <?php if ($index < 3): ?>
                                    <span class="badge bg-warning"><?= $index + 1 ?></span>
                                <?php else: ?>
                                    <?= $index + 1 ?>
                                <?php endif; ?>
                            </td>
                            <td><?= Html::encode($author['fullName']) ?></td>
                            <td>
                                <span class="badge bg-primary"><?= $author['bookCount'] ?></span>
                            </td>
                            <td>
                                <?= Html::a('Просмотр', ['/author/view', 'id' => $author['id']], ['class' => 'btn btn-sm btn-outline-primary']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            За выбранный год нет данных о книгах авторов.
        </div>
    <?php endif; ?>
</div>
