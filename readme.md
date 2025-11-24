
## 1) Инициализация БД (MySQL)

*  Создать БД `taskapp` и таблицу:

  ```sql
  CREATE DATABASE taskapp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
  USE taskapp;
  CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    completed TINYINT(1) NOT NULL DEFAULT 0
  );
  ```
*  Проверить доступы MySQL-пользователя (подключение, права `INSERT/SELECT`).

## 2) Конфигурация проекта

*  Добавить `config.php` с секциями:

  * `db.dsn`, `db.user`, `db.pass`, `db.options`
  * `storage.file` → путь к `storage/tasks.json`
  * `repository` → `mysql | file | memory`
*  Создать папку `storage/` с правами на запись.

## 3) Интерфейс репозитория

*  Обновить `App\Repository\TaskRepositoryInterface`:

  *  `findAll(): array`
  *  `add(App\Model\Task $task): void`

## 4) Репозиторий файловой сериализации (JSON)

*  Создать `App/Repository/FileTaskRepository.php`.
*  Реализовать:

  *  `findAll()` — чтение JSON → `Task[]` (учесть пустой/битый файл).
  *  `add()` — добавление записи и сохранение JSON.
*  Сериализация в UTF-8, `JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT`.

## 5) Репозиторий MySQL (PDO, без ORM)

*  Создать `App/Repository/MySqlTaskRepository.php`.
*  Конструктор принимает `PDO` (`ERRMODE_EXCEPTION`, `utf8mb4`).
*  Реализовать:

  *  `findAll()` — `SELECT title, completed FROM tasks ORDER BY id DESC`.
  *  `add()` — `INSERT` через подготовленное выражение.

## 6) DI и выбор реализации

*  Зарегистрировать в контейнере:

  *  `PDO`
  *  `FileTaskRepository`
  *  `MySqlTaskRepository`
*  Привязка `TaskRepositoryInterface` → реализация по `config['repository']`.
*  Обновить `index.php` для загрузки конфига и регистрации сервисов.

## 7) Контроллер, роутинг и UI

*  `TaskController::add()`:

  *  GET — вывод формы (`<input name="title">`).
  *  POST — валидация, `repository->add()`, редирект на `?route=task/list`.
*  В `View/task_list.php` — ссылка «Добавить задачу».
*  Убедиться, что список использует репозиторий из контейнера.

## 8) Проверки и отладка

*  Режим `file`: добавление/чтение в `storage/tasks.json`.
*  Режим `mysql`: добавление/чтение из таблицы `tasks`.
*  Негативные кейсы: отсутствующий/повреждённый JSON, недоступная БД.

## 9) Критерии приёмки

*  Интерфейс обновлён и используется контроллером.
*  Оба репозитория корректно работают (одинаковое поведение).
*  Переключение реализаций через конфиг/ENV.
*  Добавление задач доступно в обоих режимах, без фатальных ошибок.

---

### Дополнительно

*  В `FileTaskRepository` — блокировки `flock()` при записи.
*  Методы `toggleComplete()` и `delete()` в обоих репозиториях.
*  Пагинация в MySQL и индексы по частым запросам.
*  Логирование и унифицированная обработка исключений.
