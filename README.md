# Каталог книг

Тестовое веб-приложение для демонстрации.

## Описание

Приложение построено на фреймворке Yii2 с использованием DDD (Domain-Driven Design) архитектуры. Позволяет управлять книгами, авторами и подписками на новые поступления.

## Технические требования

### Для разработки с Docker (рекомендуется)
- Docker 20.10+
- Docker Compose 2.0+
- Make (опционально, для удобства)

### Для локальной разработки
- PHP 8.2+
- MySQL 8.0+ / MariaDB 10.5+ или PostgreSQL 13+
- Redis 7.0+
- Composer 2.0+
- Nginx 1.18+ или Apache 2.4+
- Git

## Установка

### Способ 1: Быстрая установка через Makefile (рекомендуется)

```bash
# Клонировать репозиторий
git clone <repository-url>
cd books-storage

# Установить и запустить приложение одной командой
make install
```

Эта команда автоматически выполнит:
- Создание файла `.env` из `.env.example`
- Сборку Docker образов
- Запуск контейнеров
- Проверку статуса сервисов

Приложение будет доступно по адресу: `http://localhost:8000`

### Способ 2: Пошаговая установка через Docker

1. Клонировать репозиторий:
```bash
git clone <repository-url>
cd books-storage
```

2. Создать файл окружения:
```bash
cp .env.example .env
```

3. Настроить переменные окружения в файле `.env` (при необходимости)

4. Запустить приложение:
```bash
make build
make up
```

5. Приложение будет доступно по адресу: `http://localhost:8000`

### Способ 3: Управление через Makefile

```bash
# Собрать и запустить
make build && make up

# Просмотр логов
make logs

# Войти в контейнер приложения
make shell

# Подключиться к MySQL
make mysql

# Подключиться к Redis
make redis

# Остановить контейнеры
make down

# Перезапустить
make restart

# Полная очистка
make clean
```

### Способ 4: Ручная установка (без Docker)

1. Клонировать репозиторий:
```bash
git clone <repository-url>
cd books-storage
```

2. Установить зависимости:
```bash
composer install
```

3. Настроить веб-сервер:
   - Указать корневую директорию на `web/`
   - Настроить URL rewriting для Yii2

4. Создать базу данных и настроить подключение в `config/db.php`

5. Запустить миграции:
```bash
./yii migrate
```

6. Настроить права доступа:
```bash
chmod -R 777 runtime/
chmod -R 777 web/assets/
```

## Структура проекта

Проект организован по принципам Domain-Driven Design (DDD) с четким разделением слоев:

```
src/
├── Domain/                   # Доменный слой - бизнес-логика и правила
│   ├── Author/               # Доменная модель автора
│   │   ├── AuthorModel.php   # Основная модель автора
│   │   └── ValueObject/      # Объекты-значения
│   │       └── AuthorName.php # Имя автора как объект-значение
│   ├── Book/                 # Доменная модель книги
│   │   ├── BookModel.php     # Основная модель книги
│   │   └── BookAuthorModel.php # Связь книга-автор
│   ├── User/                 # Доменная модель пользователя
│   │   └── UserModel.php     # Модель пользователя
│   ├── Subscription/         # Доменная модель подписки
│   │   └── SubscriptionModel.php # Модель подписки
│   ├── Event/                # Доменные события
│   │   ├── DomainEventInterface.php # Интерфейс доменного события
│   │   ├── Author/           # События автора
│   │   ├── Book/             # События книги
│   │   └── Subscription/     # События подписки
│   └── Repository/           # Интерфейсы репозиториев
│       ├── RepositoryInterface.php # Базовый интерфейс
│       ├── AuthorRepositoryInterface.php # Репозиторий авторов
│       ├── BookRepositoryInterface.php # Репозиторий книг
│       └── ...               # Другие интерфейсы репозиториев
├── Application/              # Слой приложения - координация доменных объектов
│   ├── Command/              # Команды (CQRS)
│   │   ├── CommandInterface.php # Базовый интерфейс команды
│   │   ├── CreateBookCommand.php # Команда создания книги
│   │   ├── UpdateBookCommand.php # Команда обновления книги
│   │   └── Handler/          # Обработчики команд
│   ├── Query/                # Запросы (CQRS)
│   │   ├── QueryInterface.php # Базовый интерфейс запроса
│   │   ├── GetAuthorQuery.php # Запрос получения автора
│   │   └── Handler/          # Обработчики запросов
│   └── Service/              # Сервисы приложения
│       ├── AuthorService.php # Сервис для работы с авторами
│       ├── PaginationService.php # Сервис пагинации
│       └── ReportService.php # Сервис отчетов
├── Infrastructure/           # Инфраструктурный слой - внешние сервисы и БД
│   ├── Database/             # Работа с базой данных
│   │   ├── Migration/        # Миграции БД
│   │   ├── Repository/       # Реализации репозиториев
│   │   └── Pagination/       # Пагинация
│   ├── Config/               # Конфигурация и DI контейнер
│   ├── Event/                # Система событий
│   │   ├── EventDispatcher.php # Диспетчер событий
│   │   └── Listener/         # Слушатели событий
│   ├── Queue/                # Очереди и фоновые задачи
│   ├── External/             # Внешние сервисы (SMS, API)
│   └── Environment/          # Загрузка переменных окружения
├── Presentation/             # Слой представления - контроллеры и представления
│   ├── Controller/           # Контроллеры
│   │   ├── AuthorController.php # Контроллер авторов
│   │   ├── BookController.php # Контроллер книг
│   │   └── ReportController.php # Контроллер отчетов
│   └── View/                 # Представления
│       ├── author/           # Представления авторов
│       ├── book/             # Представления книг
│       ├── layouts/          # Макеты страниц
│       └── widgets/          # Виджеты
└── Validation/               # Валидация данных
    ├── AuthorValidation.php  # Валидация авторов
    ├── BookValidation.php    # Валидация книг
    └── UserValidation.php    # Валидация пользователей
```

### Принципы архитектуры:

- **Domain Layer** - содержит бизнес-логику, модели и правила домена
- **Application Layer** - координирует доменные объекты, реализует use cases
- **Infrastructure Layer** - обеспечивает техническую реализацию (БД, внешние сервисы)
- **Presentation Layer** - отвечает за отображение данных пользователю

### CQRS (Command Query Responsibility Segregation):
- **Commands** - изменяют состояние системы (создание, обновление, удаление)
- **Queries** - только читают данные без изменения состояния
- **Handlers** - обрабатывают команды и запросы

## Миграции

Базовые миграции

- `m240101_000001_create_user_table.php` - Пользователи
- `m240101_000002_create_author_table.php` - Авторы
- `m240101_000003_create_book_table.php` - Книги
- `m240101_000004_create_book_author_table.php` - Связь книг и авторов
- `m240101_000005_create_subscription_table.php` - Подписки

## Лицензия

MIT
