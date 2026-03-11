## Socket — чат на Laravel 12 + Livewire + Reverb

Этот репозиторий: [`LeonidGorlovan/socket`](https://github.com/LeonidGorlovan/socket).

Приложение — это чат, построенный на Laravel 12, Livewire и Laravel Reverb (WebSocket‑сервер) с обработкой сообщений через очереди.

---

## 1. Требования

- **PHP** ≥ 8.2
- **Composer**
- **Node.js** (рекомендуется ≥ 20) и **npm**
- **Redis** (для очередей и Reverb scaling)
- Любая поддерживаемая Laravel СУБД (по умолчанию в примере используется **SQLite**)

---

## 2. Клонирование и базовая установка

```bash
git clone https://github.com/LeonidGorlovan/socket.git
cd socket

# Установка PHP-зависимостей
composer install

# Создаем .env и ключ приложения
cp .env.example .env
php artisan key:generate

# (опционально) создаем файл БД SQLite
mkdir -p database
touch database/database.sqlite

# Выполняем миграции
php artisan migrate

# Устанавливаем JS-зависимости
npm install
```

Для быстрой полной начальной настройки можно также использовать встроенный скрипт:

```bash
composer setup
```

Он выполнит `composer install`, создаст `.env`, сгенерирует ключ, применит миграции и соберет фронтенд.

---

## 3. Настройка окружения (.env)

Откройте файл `.env` и при необходимости измените:

- **Базовый URL приложения**

```env
APP_URL=http://localhost
```

- **База данных** (по умолчанию SQLite):

```env
DB_CONNECTION=sqlite
```

или настройте соединение с другой СУБД (MySQL/PostgreSQL и т.д.).

- **Redis и очереди**:

```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

Убедитесь, что Redis запущен локально.

- **Reverb (WebSocket‑сервер)**:

```env
REVERB_APP_ID=local-app
REVERB_APP_KEY=local-key
REVERB_APP_SECRET=local-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

Для локальной разработки достаточно задать любые согласованные значения `REVERB_APP_ID/KEY/SECRET`.

---

## 4. Сборка ассетов

Для однократной сборки фронтенда:

```bash
npm run build
```

Для режима разработки с автообновлением:

```bash
npm run dev
```

---

## 5. Запуск приложения (HTTP + Reverb + очереди)

### Вариант 1: через скрипт Composer

В одном терминале:

```bash
# HTTP-сервер, очереди, логи и Vite в одном процессе
composer dev
```

Скрипт `dev` внутри запускает:

- `php artisan serve`
- `php artisan queue:listen --tries=1 --timeout=0`
- `php artisan pail --timeout=0`
- `npm run dev`

Во **втором терминале** запустите Reverb:

```bash
php artisan reverb:start
```

После этого приложение будет доступно по адресу, указанному в `APP_URL` (по умолчанию `http://localhost:8000`), а WebSocket‑соединение — на `ws://localhost:8080`.

### Вариант 2: команды по отдельности

Терминал 1 — HTTP-сервер:

```bash
php artisan serve
```

Терминал 2 — обработчик очередей:

```bash
php artisan queue:work --tries=1 --timeout=0
```

Терминал 3 — Reverb:

```bash
php artisan reverb:start
```

Терминал 4 — фронтенд (Vite):

```bash
npm run dev
```

---

## 6. Тесты и качество кода

Запуск тестов:

```bash
php artisan test
```

Проверка и автоформатирование PHP-кода (Pint):

```bash
composer lint      # авто-исправление
composer lint:check  # только проверка
```

---

## 7. Типичный рабочий цикл разработчика

1. Клонировать репозиторий и выполнить базовую установку.
2. Настроить `.env` (БД, Redis, Reverb).
3. Запустить Redis.
4. Запустить `composer dev`.
5. В отдельном терминале запустить `php artisan reverb:start`.
6. Открыть приложение в браузере и использовать чат в реальном времени.

