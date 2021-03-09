# Yandex Market YML generator

Генератор YML-файла для ЯндексМаркета и других сервисов Яндекс, использующих формат YML.

Библиотека создана для упрощения создания YML файлов для ЯндексМаркета и снижения потребления памяти необходимой для
его генерации за счет опции выгрузки данных из памяти в файл по мере создания.

## Установка

```shell
 composer require rame0/yandex-market-object
 ```

## Базовое использование

```php
use rame0\Generators\YandexMarket\Category;
use rame0\Generators\YandexMarket\Currency;
use rame0\Generators\YandexMarket\Offer;
use rame0\Generators\YandexMarket\YML;

// Инициализация
$yml = new YML('MyShop', 'MyCorp', 'https://www.site.ru/');
$yml->setDate(strtotime('2021-03-08T17:20:28+00:00'));

// Начало записи
$yml->startYML();

// Добавление валют
$yml->startElement('currencies');
$yml->writeItem(new Currency(Currency::$CUR_RUB));
$yml->endElement();

// Добавление категорий
$yml->startElement('categories');
$yml->writeItem(new Category(1, 'cat1'));
$yml->writeItem(new Category(2, 'cat2'));
$yml->writeItem(new Category(3, 'cat3', 1));
$yml->endElement();

// Добавление оферов
$yml->startElement('offers');
$yml->writeItem(new Offer(1, 'Товар 1', 1));
$yml->writeItem(new Offer(2, 'Товар 2', 2));
$yml->writeItem(new Offer(3, 'Товар 3', 3));
$yml->writeItem(new Offer(4, 'Товар 4', 3));
$yml->writeItem(new Offer(5, 'Товар 5', 1));
$yml->endElement();

// Завершение записи
$yml->endYML();

// Вывод результата
echo $yml->flush();
```

## Сохранение в файл

```php
use rame0\Generators\YandexMarket\YML;

$yml = new YML('MyShop', 'MyCorp', 'https://www.site.ru/');
$yml->setDate(strtotime('2021-03-08T17:20:28+00:00'));


$yml->setOutputFile('./test.yml');

//...

```

## Сохранение в файл "на лету"

```php
use rame0\Generators\YandexMarket\YML;

$yml = new YML('MyShop', 'MyCorp', 'https://www.site.ru/');
$yml->setDate(strtotime('2021-03-08T17:20:28+00:00'));


$yml->setOutputFile('./test.yml');
// Выводить в файл на лету
$yml->setIsOutputOnTheFly(true);
// Выводить через каждые 100 добавленных записей
$yml->setFlushEvery(100);

//...

```