<?php

namespace src\Presentation\Controller;

use src\Infrastructure\Config\ContainerManager;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use src\Domain\Author\AuthorModel;
use src\Domain\Repository\AuthorRepositoryInterface;
use src\Validation\AuthorValidation;
use src\Application\Command\Handler\CommandBus;
use src\Application\Query\Handler\QueryBus;
use src\Application\Command\CreateAuthorCommand;
use src\Application\Command\UpdateAuthorCommand;
use src\Application\Command\DeleteAuthorCommand;
use src\Application\Query\GetAuthorQuery;
use src\Application\Query\GetTopAuthorsQuery;
use src\Application\Service\PaginationService;
use yii\web\Response;

class AuthorController extends Controller
{
    public $layout = '@views/layouts/main';
    
    private AuthorRepositoryInterface $authorRepository;
    private CommandBus $commandBus;
    private QueryBus $queryBus;
    private PaginationService $paginationService;
    
    public function __construct($id, $module, $config = [])
    {
        $this->authorRepository = ContainerManager::get(AuthorRepositoryInterface::class);
        $this->commandBus = ContainerManager::get(CommandBus::class);
        $this->queryBus = ContainerManager::get(QueryBus::class);
        $this->paginationService = ContainerManager::get('AuthorPaginationService');
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
                        'actions' => ['index', 'view'],
                        'roles' => ['?', '@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $page = (int)Yii::$app->request->get('page', 1);
        $search = Yii::$app->request->get('search', '');
        $active = Yii::$app->request->get('active', '');
        
        $criteria = [];
        $orderBy = ['lastName' => SORT_ASC, 'firstName' => SORT_ASC];
        
        if (!empty($search)) {
            $criteria = ['or', ['like', 'firstName', $search], ['like', 'lastName', $search], ['like', 'middleName', $search]];
        }
        
        if ($active !== '') {
            $criteria['isActive'] = (bool)$active;
        }
        
        $authorList = $this->paginationService->getPage($page, $criteria, $orderBy);
        $paginationInfo = $this->paginationService->getPaginationInfo($page, $criteria);

        return $this->render('@views/author/index', [
            'authorList' => $authorList,
            'paginationInfo' => $paginationInfo,
            'search' => $search,
            'active' => $active,
        ]);
    }

    public function actionView(int $id): string
    {
        $query = new GetAuthorQuery($id);
        $author = $this->queryBus->dispatch($query);
        
        if (is_null($author)) {
            throw new NotFoundHttpException('Автор не найден');
        }

        return $this->render('@views/author/view', [
            'author' => $author,
        ]);
    }

    public function actionCreate(): string|Response
    {
        $author = new AuthorModel();
        $validation = new AuthorValidation();

        if (Yii::$app->request->isPost) {
            $validation = AuthorValidation::fromRequest(Yii::$app->request);
            
            if ($validation->validate()) {
                try {
                    $command = new CreateAuthorCommand($validation);
                    $authorId = $this->commandBus->dispatch($command);
                    
                    Yii::$app->session->setFlash('success', 'Автор успешно создан');
                    return $this->redirect(['view', 'id' => $authorId]);
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', sprintf('Ошибка при создании автора: %s', $e->getMessage()));
                }
            }
        }

        return $this->render('@views/author/create', [
            'author' => $author,
            'validation' => $validation,
        ]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $author = $this->findAuthor($id);
        $validation = new AuthorValidation();

        if (Yii::$app->request->isPost) {
            $validation = AuthorValidation::fromRequest(Yii::$app->request);
            
            if ($validation->validate()) {
                try {
                    $command = new UpdateAuthorCommand($id, $validation);
                    $this->commandBus->dispatch($command);
                    
                    Yii::$app->session->setFlash('success', 'Автор успешно обновлен');
                    return $this->redirect(['view', 'id' => $id]);
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', sprintf('Ошибка при обновлении автора: %s', $e->getMessage()));
                }
            }
        } else {
            $validation->firstName = $author->firstName;
            $validation->lastName = $author->lastName;
            $validation->middleName = $author->middleName;
            $validation->isActive = $author->isActive;
        }

        return $this->render('@views/author/update', [
            'author' => $author,
            'validation' => $validation,
        ]);
    }

    public function actionDelete(int $id): Response
    {
        try {
            $command = new DeleteAuthorCommand($id);
            $this->commandBus->dispatch($command);
            
            Yii::$app->session->setFlash('success', 'Автор успешно удален');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', sprintf('Ошибка при удалении автора: %s', $e->getMessage()));
        }

        return $this->redirect(['index']);
    }

    public function actionTopAuthors(): string
    {
        $year = Yii::$app->request->get('year', date('Y'));
        $query = new GetTopAuthorsQuery(10, (int)$year);
        $topAuthors = $this->queryBus->dispatch($query);

        return $this->render('@views/author/top-authors', [
            'topAuthors' => $topAuthors,
            'year' => $year,
        ]);
    }

    private function findAuthor(int $id): AuthorModel
    {
        $author = $this->authorRepository->findById($id);
        
        if (is_null($author)) {
            throw new NotFoundHttpException('Автор не найден');
        }

        return $author;
    }
}
