<?php

namespace console;

use src\Infrastructure\Config\ContainerManager;
use src\Domain\Repository\AuthorRepositoryInterface;
use src\Domain\Repository\BookRepositoryInterface;
use src\Domain\Repository\UserRepositoryInterface;
use src\Domain\Repository\SubscriptionRepositoryInterface;
use src\Domain\Author\AuthorModel;
use src\Domain\Book\BookModel;
use src\Domain\Book\BookAuthorModel;
use src\Domain\User\UserModel;
use src\Domain\Subscription\SubscriptionModel;
use yii\console\Controller;

class SeedController extends Controller
{
    public function actionIndex(): void
    {        
        try {
            $this->seedUsers();
            $this->seedAuthors();
            $this->seedBooks();
            $this->seedSubscriptions();
            
            $this->stdout("База данных успешно заполнена тестовыми данными!\n");
        } catch (\Exception $e) {
            $this->stderr("Ошибка при заполнении базы данных: " . $e->getMessage() . "\n");
        }
    }

    private function seedUsers(): void
    {
        $this->stdout("Создаем пользователей...\n");
        
        $userRepository = ContainerManager::get(UserRepositoryInterface::class);
        
        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => 'admin',
            ],
            [
                'username' => 'user1',
                'email' => 'user1@example.com',
                'password' => 'password',
            ],
            [
                'username' => 'user2',
                'email' => 'user2@example.com',
                'password' => 'password',
            ],
        ];

        foreach ($users as $userData) {
            $existingUser = $userRepository->findByUsername($userData['username']);
            if (!is_null($existingUser)) {
                $this->stdout("Пользователь {$userData['username']} уже существует, пропускаем\n");
                continue;
            }
            
            $user = new UserModel();
            $user->username = $userData['username'];
            $user->email = $userData['email'];
            $user->setPassword($userData['password']);
            $user->generateAuthKey();
            $user->status = UserModel::STATUS_ACTIVE;
            
            $userRepository->save($user);
            $this->stdout("Создан пользователь: {$userData['username']}\n");
        }
    }

    private function seedAuthors(): void
    {
        $this->stdout("Создаем авторов...\n");
        
        $authorRepository = ContainerManager::get(AuthorRepositoryInterface::class);
        
        $authors = [
            [
                'firstName' => 'Лев',
                'lastName' => 'Толстой',
                'middleName' => 'Николаевич',
            ],
            [
                'firstName' => 'Федор',
                'lastName' => 'Достоевский',
                'middleName' => 'Михайлович',
            ],
            [
                'firstName' => 'Антон',
                'lastName' => 'Чехов',
                'middleName' => 'Павлович',
            ],
            [
                'firstName' => 'Александр',
                'lastName' => 'Пушкин',
                'middleName' => 'Сергеевич',
            ],
            [
                'firstName' => 'Николай',
                'lastName' => 'Гоголь',
                'middleName' => 'Васильевич',
            ],
            [
                'firstName' => 'Михаил',
                'lastName' => 'Булгаков',
                'middleName' => 'Афанасьевич',
            ],
            [
                'firstName' => 'Борис',
                'lastName' => 'Пастернак',
                'middleName' => 'Леонидович',
            ],
            [
                'firstName' => 'Александр',
                'lastName' => 'Солженицын',
                'middleName' => 'Исаевич',
            ],
        ];

        foreach ($authors as $authorData) {
            $existingAuthor = $authorRepository->findByLastName($authorData['lastName']);
            if (!empty($existingAuthor)) {
                $this->stdout("Автор {$authorData['firstName']} {$authorData['lastName']} уже существует, пропускаем\n");
                continue;
            }
            
            $author = new AuthorModel();
            $author->firstName = $authorData['firstName'];
            $author->lastName = $authorData['lastName'];
            $author->middleName = $authorData['middleName'];
            $author->isActive = true;
            
            $authorRepository->save($author);
            $this->stdout("Создан автор: {$authorData['firstName']} {$authorData['lastName']}\n");
        }
    }

    private function seedBooks(): void
    {
        $this->stdout("Создаем книги...\n");
        
        $bookRepository = ContainerManager::get(BookRepositoryInterface::class);
        $authorRepository = ContainerManager::get(AuthorRepositoryInterface::class);
        
        $books = [
            [
                'title' => 'Война и мир',
                'publicationYear' => 2020,
                'isbn' => '978-5-17-087761-8',
                'description' => 'Роман-эпопея Льва Толстого, описывающий русское общество в эпоху наполеоновских войн.',
                'coverImage' => 'war_and_peace.jpg',
                'authorIds' => [1],
            ],
            [
                'title' => 'Анна Каренина',
                'publicationYear' => 2021,
                'isbn' => '978-5-17-087762-5',
                'description' => 'Роман о трагической любви замужней дамы Анны Карениной.',
                'coverImage' => 'anna_karenina.jpg',
                'authorIds' => [1],
            ],
            [
                'title' => 'Преступление и наказание',
                'publicationYear' => 2022,
                'isbn' => '978-5-17-087763-2',
                'description' => 'Роман о студенте Раскольникове, совершившем убийство.',
                'coverImage' => 'crime_and_punishment.jpg',
                'authorIds' => [2],
            ],
            [
                'title' => 'Идиот',
                'publicationYear' => 2023,
                'isbn' => '978-5-17-087764-9',
                'description' => 'Роман о князе Мышкине, человеке исключительной доброты.',
                'coverImage' => 'idiot.jpg',
                'authorIds' => [2],
            ],
            [
                'title' => 'Вишневый сад',
                'publicationYear' => 2024,
                'isbn' => '978-5-17-087765-6',
                'description' => 'Пьеса о продаже имения с вишневым садом.',
                'coverImage' => 'cherry_orchard.jpg',
                'authorIds' => [3],
            ],
            [
                'title' => 'Евгений Онегин',
                'publicationYear' => 2020,
                'isbn' => '978-5-17-087766-3',
                'description' => 'Роман в стихах о любви и судьбе.',
                'coverImage' => 'eugene_onegin.jpg',
                'authorIds' => [4],
            ],
            [
                'title' => 'Мертвые души',
                'publicationYear' => 2021,
                'isbn' => '978-5-17-087767-0',
                'description' => 'Поэма о путешествии Чичикова по России.',
                'coverImage' => 'dead_souls.jpg',
                'authorIds' => [5],
            ],
            [
                'title' => 'Мастер и Маргарита',
                'publicationYear' => 2022,
                'isbn' => '978-5-17-087768-7',
                'description' => 'Роман о добре и зле, любви и предательстве.',
                'coverImage' => 'master_and_margarita.jpg',
                'authorIds' => [6],
            ],
            [
                'title' => 'Доктор Живаго',
                'publicationYear' => 2023,
                'isbn' => '978-5-17-087769-4',
                'description' => 'Роман о судьбе интеллигенции в революции.',
                'coverImage' => 'doctor_zhivago.jpg',
                'authorIds' => [7],
            ],
            [
                'title' => 'Архипелаг ГУЛАГ',
                'publicationYear' => 2024,
                'isbn' => '978-5-17-087770-0',
                'description' => 'Художественно-историческое исследование советской репрессивной системы.',
                'coverImage' => 'gulag_archipelago.jpg',
                'authorIds' => [8],
            ],
        ];

        foreach ($books as $bookData) {
            $existingBook = $bookRepository->findByIsbn($bookData['isbn']);
            if (!is_null($existingBook)) {
                $this->stdout("Книга '{$bookData['title']}' уже существует, пропускаем\n");
                continue;
            }
            
            $book = new BookModel();
            $book->title = $bookData['title'];
            $book->publicationYear = $bookData['publicationYear'];
            $book->isbn = $bookData['isbn'];
            $book->description = $bookData['description'];
            $book->coverImage = $bookData['coverImage'];
            
            $bookRepository->save($book);
            
            $authorNames = [
                1 => 'Толстой',
                2 => 'Достоевский',
                3 => 'Чехов',
                4 => 'Пушкин',
                5 => 'Гоголь',
                6 => 'Булгаков',
                7 => 'Пастернак',
                8 => 'Солженицын',
            ];
            
            foreach ($bookData['authorIds'] as $authorIndex) {
                $authorLastName = $authorNames[$authorIndex];
                $authors = $authorRepository->findByLastName($authorLastName);
                
                if (!empty($authors)) {
                    $author = $authors[0];
                    $bookAuthor = new BookAuthorModel();
                    $bookAuthor->bookId = $book->id;
                    $bookAuthor->authorId = $author->id;
                    $bookAuthor->save();
                }
            }
            
            $this->stdout("Создана книга: {$bookData['title']}\n");
        }
    }

    private function seedSubscriptions(): void
    {
        $this->stdout("Создаем подписки...\n");
        
        $subscriptionRepository = ContainerManager::get(SubscriptionRepositoryInterface::class);
        $authorRepository = ContainerManager::get(AuthorRepositoryInterface::class);
        
        $subscriptions = [
            [
                'phone' => '+79001234567',
                'email' => 'user1@example.com',
                'authorId' => 1,
                'status' => SubscriptionModel::STATUS_ACTIVE,
            ],
            [
                'phone' => '+79001234568',
                'email' => 'user2@example.com',
                'authorId' => 2,
                'status' => SubscriptionModel::STATUS_ACTIVE,
            ],
            [
                'phone' => '+79001234569',
                'email' => 'user3@example.com',
                'authorId' => 3,
                'status' => SubscriptionModel::STATUS_ACTIVE,
            ],
            [
                'phone' => '+79001234570',
                'email' => 'user4@example.com',
                'authorId' => 4,
                'status' => SubscriptionModel::STATUS_INACTIVE,
            ],
        ];

        foreach ($subscriptions as $subscriptionData) {
            $existingSubscription = $subscriptionRepository->findByPhone($subscriptionData['phone']);
            if (!is_null($existingSubscription)) {
                $this->stdout("Подписка с телефоном {$subscriptionData['phone']} уже существует, пропускаем\n");
                continue;
            }
            
            $authorNames = [
                1 => 'Толстой',
                2 => 'Достоевский',
                3 => 'Чехов',
                4 => 'Пушкин',
            ];
            
            $authorLastName = $authorNames[$subscriptionData['authorId']];
            $authors = $authorRepository->findByLastName($authorLastName);
            
            if (!empty($authors)) {
                $author = $authors[0];
                
                $subscription = new SubscriptionModel();
                $subscription->phone = $subscriptionData['phone'];
                $subscription->email = $subscriptionData['email'];
                $subscription->authorId = $author->id;
                $subscription->status = $subscriptionData['status'];
                
                $subscriptionRepository->save($subscription);
                $this->stdout("Создана подписка: {$subscriptionData['phone']} на автора {$author->getFullName()}\n");
            }
        }
    }
}
