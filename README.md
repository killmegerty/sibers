# Пререквизиты

Из корня проекта, установка зависимостей
```sh
$ composer install
```

Создание БД
```sh
mysql> CREATE DATABASE minenko_konstantin DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
mysql> GRANT ALL PRIVILEGES ON minenko_konstantin.* TO minenko_konstantin@localhost IDENTIFIED BY 'minenko_konstantin';
```

Накатить дамп
```sh
$ mysql -uminenko_konstantin -pminenko_konstantin minenko_konstantin < __sql/minenko_konstantin-2018-06-08.sql
```


# Комментарии по проекту

Корень проекта для веб-сервера
```sh
$ /webroot
```

Используется модель MVC

Яндекс иногда блочит запросы.
Реализовано через парсер sunra/php-simple-html-dom-parser (http://simplehtmldom.sourceforge.net/manual.htm), настройки поисковых систем лежат в БД , в таблице search_engines, чтобы добавить новый - нужно просто добавить запись в таблицу.

Конкретно по колонкам в search_engines таблице,

`query_url` - адрес, куда будет потом прибавляться query - запрос из инпута на главной проекта.

`item_block_selector` - селектор блока, который содержит всю информацию по одной единице результатов

`item_block_child_title_selector`, `item_block_child_href_selector`, `item_block_child_description_selector` - селекторы для титла, ссылки и краткого описания, искать будет в границах блока , который передан в колонке `item_block_selector`

Переход по ссылкам не всегда корректно работает, в случае с гуглом - там скорее всего антипарсер, к ссылкам дописывается рандомные строки, поэтому ссылки брал из div блоков, обычно они под тайтлом итема.
