## Socket — чат на Laravel 12 + Livewire + Reverb

Цей репозиторій: [`LeonidGorlovan/socket`](https://github.com/LeonidGorlovan/socket).

Застосунок — це чат, побудований на Laravel 12, Livewire та Laravel Reverb (WebSocket‑сервер) з обробкою повідомлень через черги.

---

## 1. Вимоги

- **PHP** ≥ 8.2
- **Composer**
- **Node.js** (рекомендовано ≥ 20) та **npm**
- **Redis** (для черг і Reverb scaling)
- Будь‑яка підтримувана Laravel СУБД (за замовчуванням у прикладі використовується **SQLite**)

---

## 2. Клонування та базове налаштування

```bash
git clone https://github.com/LeonidGorlovan/socket.git
cd socket

# Встановлення PHP‑залежностей
composer install

# Створюємо .env і ключ застосунку
cp .env.example .env
php artisan key:generate

# (опційно) створюємо файл БД SQLite
mkdir -p database
touch database/database.sqlite

# Виконуємо міграції
php artisan migrate

# Встановлюємо JS‑залежності
npm install
```

Для швидкого повного початкового налаштування можна також використати вбудований скрипт:

```bash
composer setup
```

Він виконає `composer install`, створить `.env`, згенерує ключ, застосує міграції та збере фронтенд.

---

## 3. Налаштування середовища (.env)

Відкрийте файл `.env` і за потреби змініть:

- **Базовий URL застосунку**

```env
APP_URL=http://localhost
```

- **База даних** (за замовчуванням SQLite):

```env
DB_CONNECTION=sqlite
```

або налаштуйте з’єднання з іншою СУБД (MySQL/PostgreSQL тощо).

- **Redis і черги**:

```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

Переконайтеся, що Redis запущений локально.

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

Для локальної розробки достатньо задати будь‑які узгоджені значення `REVERB_APP_ID/KEY/SECRET`.

---

## 4. Збірка ассетів

Для одноразової збірки фронтенда:

```bash
npm run build
```

Для режиму розробки з автооновленням:

```bash
npm run dev
```

---

## 5. Запуск застосунку (HTTP + Reverb + черги)

### Варіант 1: через скрипт Composer

В одному терміналі:

```bash
# HTTP‑сервер, черги, логи й Vite в одному процесі
composer dev
```

Скрипт `dev` всередині запускає:

- `php artisan serve`
- `php artisan queue:listen --tries=1 --timeout=0`
- `php artisan pail --timeout=0`
- `npm run dev`

У **другому терміналі** запустіть Reverb:

```bash
php artisan reverb:start
```

Після цього застосунок буде доступний за адресою, вказаною в `APP_URL` (за замовчуванням `http://localhost:8000`), а WebSocket‑з’єднання — на `ws://localhost:8080`.

### Варіант 2: команди окремо

Термінал 1 — HTTP‑сервер:

```bash
php artisan serve
```

Термінал 2 — обробник черг:

```bash
php artisan queue:work --tries=1 --timeout=0
```

Термінал 3 — Reverb:

```bash
php artisan reverb:start
```

Термінал 4 — фронтенд (Vite):

```bash
npm run dev
```

---

## 6. Тести та якість коду

Запуск тестів:

```bash
php artisan test
```

Перевірка та автоформатування PHP‑коду (Pint):

```bash
composer lint      # авто‑виправлення
composer lint:check  # лише перевірка
```

---

## 7. Типовий робочий цикл розробника

1. Клонувати репозиторій і виконати базове налаштування.
2. Налаштувати `.env` (БД, Redis, Reverb).
3. Запустити Redis.
4. Запустити `composer dev`.
5. В окремому терміналі запустити `php artisan reverb:start`.
6. Відкрити застосунок у браузері та використовувати чат у реальному часі.

