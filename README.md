### Запуск проекта

1. Клонируем проект `git clone https://github.com/ILeshaRW/ipr-laravel-blog.git`.
1. Переходим в директорию проекта `cd ipr-laravel-blog`.
1. Создаем файл конфигурации `.env` командой `make env`. 
1. в docker/.env ввести имя пользователя, группы и внести изменения в другие настройки если потребуется
1. Запускаем проект командой `make up`.
1. Установить зависимости командой `make composer-i`
1. Сгенирировать ключ приложения, командой `make key`
1. Установить миграции и тестовые данные командой `make migrate seed`
1. Проект с тестовыми данными доступен по http://localhost

### Полезное

1. Запуск тестов `make test`
2. Адрес mailhog http://localhost:8025
3. RabbitMQ management http://localhost:15672/
4. Telescope http://localhost/telescope/
5. Swagger http://localhost/swagger/

User по умолчанию для авторизации `lesharw@bk.ru` `qwerty123`
