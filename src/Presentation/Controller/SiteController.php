<?php

namespace src\Presentation\Controller;

use src\Infrastructure\Config\ContainerManager;
use Yii;
use yii\web\Controller;

use src\Domain\Repository\BookRepositoryInterface;
use src\Domain\Repository\AuthorRepositoryInterface;

class SiteController extends Controller
{
    public $layout = '@views/layouts/main';
    
    private BookRepositoryInterface $bookRepository;
    private AuthorRepositoryInterface $authorRepository;
    
    public function __construct($id, $module, $config = [])
    {
        $this->bookRepository = ContainerManager::get(BookRepositoryInterface::class);
        $this->authorRepository = ContainerManager::get(AuthorRepositoryInterface::class);
        parent::__construct($id, $module, $config);
    }
    
    public function actionIndex(): string
    {
        $bookList = $this->bookRepository->findWithOptions(
            criteria: [],
            orderBy: ['createdAt' => SORT_DESC],
            limit: 6
        );
        
        $authorList = $this->authorRepository->findWithOptions(
            criteria: [],
            orderBy: ['lastName' => SORT_ASC],
            limit: 8
        );
        
        return $this->render('@views/site/index', [
            'bookList' => $bookList,
            'authorList' => $authorList,
        ]);
    }

    public function actionError(): string
    {
        $exception = Yii::$app->errorHandler->exception;
        
        if ($exception !== null) {
                    return $this->render('@views/site/error', [
            'exception' => $exception,
        ]);
        }
        
        return '';
    }
}
