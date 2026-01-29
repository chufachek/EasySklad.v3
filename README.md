# Easy. склад — MVP

## Установка
1. Скопируйте содержимое репозитория в каталог `public_html` на вашем хостинге.
2. Отредактируйте `/config/config.php` и укажите параметры базы данных.
3. Импортируйте `database.sql` в MySQL.
4. Убедитесь, что Apache разрешает `.htaccess` и `mod_rewrite`.

## Проверка routing
- `https://ваш-домен/__ping.php` — проверка окружения PHP.
- `https://ваш-домен/__rewrite_test.html` — статическая проверка.
- `https://ваш-домен/__rewrite_probe` — должен попасть в `index.php`.
- `https://ваш-домен/__health` — возвращает OK и режим `clean` или `fallback`.

Если `mod_rewrite` недоступен, используйте fallback:
- `https://ваш-домен/index.php?page=login`
- `https://ваш-домен/index.php?page=app/dashboard`

## HTTPS
В `config/config.php` установите `force_https` в `true`, чтобы включить 301-редирект на HTTPS.

## Тестовый аккаунт
- Email: `test@example.com`
- Пароль: `password123`

## Структура
- `/public_html` — фронт контроллер и статические ресурсы
- `/src` — PHP логика
- `/routes` — маршруты web/API
- `/database.sql` — схема и seed

