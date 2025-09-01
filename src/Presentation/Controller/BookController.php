<?php

namespace src\Presentation\Controller;

use src\Infrastructure\Config\ContainerManager;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use src\Domain\Book\BookModel;
use src\Domain\Repository\BookRepositoryInterface;
use src\Domain\Repository\AuthorRepositoryInterface;
use src\Domain\Event\EventDispatcherInterface;
use src\Validation\BookValidation;
use src\Application\Command\Handler\CommandBus;
use src\Application\Command\CreateBookCommand;
use src\Application\Command\UpdateBookCommand;
use src\Application\Command\DeleteBookCommand;
use src\Application\Service\PaginationService;
use yii\web\Response;

class BookController extends Controller
{
    public $layout = '@views/layouts/main';
    
    private BookRepositoryInterface $bookRepository;
    private AuthorRepositoryInterface $authorRepository;
    private EventDispatcherInterface $eventDispatcher;
    private CommandBus $commandBus;
    private PaginationService $paginationService;
    
    public function __construct($id, $module, $config = [])
    {
        $this->bookRepository = ContainerManager::get(BookRepositoryInterface::class);
        $this->authorRepository = ContainerManager::get(AuthorRepositoryInterface::class);
        $this->eventDispatcher = ContainerManager::get(EventDispatcherInterface::class);
        $this->commandBus = ContainerManager::get(CommandBus::class);
        $this->paginationService = ContainerManager::get('BookPaginationService');
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
        $year = Yii::$app->request->get('year', '');
        
        $criteria = [];
        $orderBy = ['createdAt' => SORT_DESC];
        
        if (!empty($search)) {
            $criteria = ['or', ['like', 'title', $search], ['like', 'description', $search]];
        }
        
        if (!empty($year)) {
            $criteria['publicationYear'] = (int)$year;
        }
        
        $bookList = $this->paginationService->getPage($page, $criteria, $orderBy);
        $paginationInfo = $this->paginationService->getPaginationInfo($page, $criteria);

        return $this->render('@views/book/index', [
            'bookList' => $bookList,
            'paginationInfo' => $paginationInfo,
            'search' => $search,
            'year' => $year,
        ]);
    }

    public function actionView(int $id): string
    {
        $book = $this->findBook($id);

        return $this->render('@views/book/view', [
            'book' => $book,
        ]);
    }

    public function actionCreate(): string|Response
    {
        $book = new BookModel();
        $authorList = $this->authorRepository->findAll();
        $validation = new BookValidation();

        if (Yii::$app->request->isPost) {
            $validation = BookValidation::fromRequest(Yii::$app->request);
            
            if ($validation->validate()) {
                try {
                    $command = new CreateBookCommand(
                        $validation->title,
                        $validation->publicationYear,
                        $validation->description,
                        $validation->isbn,
                        $validation->coverImage,
                        Yii::$app->request->post('authorIds', [])
                    );
                    
                    $bookId = $this->commandBus->dispatch($command);
                    
                    Yii::$app->session->setFlash('success', 'Книга успешно создана');
                    return $this->redirect(['view', 'id' => $bookId]);
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', sprintf('Ошибка при создании книги: %s', $e->getMessage()));
                }
            }
        }

        return $this->render('@views/book/create', [
            'book' => $book,
            'authorList' => $authorList,
            'validation' => $validation,
        ]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $book = $this->findBook($id);
        $authorList = $this->authorRepository->findAll();
        $selectedAuthorIds = $book->getAuthorList()->select('authorId')->column();
        $validation = new BookValidation();

        if (Yii::$app->request->isPost) {
            $validation = BookValidation::fromRequest(Yii::$app->request);
            
            if ($validation->validate()) {
                try {
                    $command = new UpdateBookCommand(
                        $id,
                        Yii::$app->request->post('authorIds', []),
                        $validation,
                    );
                    
                    $this->commandBus->dispatch($command);
                    
                    Yii::$app->session->setFlash('success', 'Книга успешно обновлена');
                    return $this->redirect(['view', 'id' => $id]);
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('error', sprintf('Ошибка при обновлении книги: %s', $e->getMessage()));
                }
            }
        } else {
            $validation->title = $book->title;
            $validation->publicationYear = $book->publicationYear;
            $validation->description = $book->description;
            $validation->isbn = $book->isbn;
            $validation->coverImage = $book->coverImage;
        }

        return $this->render('@views/book/update', [
            'book' => $book,
            'authorList' => $authorList,
            'selectedAuthorIds' => $selectedAuthorIds,
            'validation' => $validation,
        ]);
    }

    public function actionDelete(int $id): Response
    {
        try {
            $command = new DeleteBookCommand($id);
            $this->commandBus->dispatch($command);
            
            Yii::$app->session->setFlash('success', 'Книга успешно удалена');
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', sprintf('Ошибка при удалении книги: %s', $e->getMessage()));
        }

        return $this->redirect(['index']);
    }

    private function findBook(int $id): BookModel
    {
        $book = $this->bookRepository->findById($id);
        
        if (is_null($book)) {
            throw new NotFoundHttpException('Книга не найдена');
        }

        return $book;
    }
}
