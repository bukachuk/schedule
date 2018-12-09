# Пример приложения "Расписание поездок курьеров в регионы"

### Установка

Скачать репозиторий
```
git clone https://github.com/bukachuk/schedule.git
```
```
cd schedule/
```
Создать базу данных
```
mysqladmin create schedule
```
Установить зависимости и настроить соединение с БД после установки
```
composer install
```
Создать схему базы данных
```
bin/console doctrine:schema:create
```
Загрузить тестовые данные в БД
```
bin/console doctrine:fixtures:load
```
Запустить сервер
```
bin/console server:start
```

