<?php

namespace src\Presentation\Controller;

use src\Infrastructure\Config\ContainerManager;
use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use src\Application\Query\Handler\QueryBus;
use src\Application\Query\GetTopAuthorsQuery;

class ReportController extends Controller
{
    public $layout = '@views/layouts/main';
    
    private QueryBus $queryBus;
    
    public function __construct($id, $module, $config = [])
    {
        $this->queryBus = ContainerManager::get(QueryBus::class);
        parent::__construct($id, $module, $config);
    }
    
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'top-authors'],
                        'roles' => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        return $this->render('@views/report/index');
    }

    public function actionTopAuthors(): string
    {
        $year = Yii::$app->request->get('year', date('Y'));
        $query = new GetTopAuthorsQuery(10, (int)$year);
        $topAuthors = $this->queryBus->dispatch($query);

        return $this->render('@views/report/top-authors', [
            'topAuthors' => $topAuthors,
            'year' => $year,
        ]);
    }
}
