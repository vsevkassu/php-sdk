# php-sdk
Библиотека интеграции с сервисом [vsevkassu.ru](https://vsevkassu.ru/) для PHP

Сервис «Всё в кассу» предназначен для удобной интеграции онлайн-касс с интернет-магазином в
полном соответствии с 54-ФЗ

## Требования

* PHP >= 5.4
* CURL

## Установка

```
git clone https://github.com/vsevkassu/php-sdk
```

```php
<?php

require __DIR__.'/php-sdk/autoloader.php';
```

## Авторизация в сервисе

Для работы необходимо иметь аккаунт в сервисе и хотя бы одну подключенную кассу

Для авторизации необходимо создать инициализировать объект vsevkassu\sdk\VsevkassuAPI, указать логин и пароль в сервис и вызвать метод авторизации.
Если авторизация прошла успешно, токен будет установлен автоматически. 


```php
<?php
use vsevkassu\sdk\VsevkassuAPI;

$api = new VsevkassuAPI();
$api->login = 'my@email.ru';
$api->password = 'mypassword';
$api->auth(); 
```

Если токен известен, то для работы с API достаточно инициализировать объект и установить токен.
Метод авторизации при этом вызывать не нужно.

```php
<?php
use vsevkassu\sdk\VsevkassuAPI;
$api = new VsevkassuAPI();
$api->token = 'xxxxxxxxxxxxxxxxxxxxxxxxx';
```

## Открытие и закрытие смены
При открытии смены можно указать кассира. Если не указывать, то смена откроется с кассиром, который указан по
умолчанию для данной кассы. 
Обязательно нужно указать системный номер кассы.

```php
<?php
$shift_id = $api->openShift(20);
```

## Создание чека
Существует два подхода к созданию чеков. Первый подход состоит в инициализации объекта vsevkassu\sdk\Receipt
и заполнения необходимых полей:
```php
<?php
use vsevkassu\sdk\Receipt;
use vsevkassu\sdk\Item;
use vsevkassu\sdk\Payment;

$Receipt = new Receipt(); //создаем новый чек
$Receipt->external_id = '1'; //внешний ID

$Receipt->is_print = true; //выводим на печать на кассе.

$Receipt->salepoint_id = 20; //системный идентификатор кассы
$Receipt->type = Receipt::TYPE_INCOME; //тип чека - приход, описание констант в Receipt.php

$Item = new Item(); //создаем новый товар
$Item->item_type = Item::ITEM_TYPE_PROD; // тип - товар, описание констант в Item.php
$Item->pay_type = Item::PAY_TYPE_FULL; //тип платежа - полная оплата, описание констант в Item.php
$Item->name = 'Наименование товара';
$Item->price = 10;
$Item->quantity = 1.1;
$Item->nds_type = Item::NDS_TYPE_WO; //тип НДС - без НДС, описание констант в Item.php

$Receipt->addItem($Item); //добавляем товар к чеку

$Payment = new Payment(); //создаем информацию об оплате
$Payment->full_sum = 11; //итоговая сумма чека
$Payment->sum_nal = 11; //получено наличными

$Receipt->setPayment($Payment); //добавляем информацию об оплате к чеку

try {
    $Receipt = $api->saveReceipt($Receipt); //отправляем чек.
// При успешной отправке вернется объект vsevkassu\sdk\Receipt
// При ошибке получим Exception
} catch (\Exception $e) {
    echo $e->getMessage();
}
```

Второй подход предполагает создание объекта чека из массива:

```php
<?php
use vsevkassu\sdk\Receipt;
use vsevkassu\sdk\Item;

$Receipt = Receipt::fromData([
    'external_id' => '1',
    'is_print' => true,
    'salepoint_id' => 20,
    'type' => Receipt::TYPE_INCOME,
    'items' => [
        [
            'item_type' => Item::ITEM_TYPE_PROD,
            'pay_type' => Item::PAY_TYPE_FULL,
            'name' => 'Наименование товара',
            'price' => 10,
            'quantity' => 1.1,
            'nds_type' => Item::NDS_TYPE_WO,
        ]
    ],
    'payment' => [
        'full_sum' => 11,
        'sum_nal' => 11
    ]
]);

try {
    $Receipt = $api->saveReceipt($Receipt); //отправляем чек.
// При успешной отправке вернется объект vsevkassu\sdk\Receipt
// При ошибке получим Exception
} catch (\Exception $e) {
    echo $e->getMessage();
}

```


## Другие задачи

Другие задачи по работе со списком чеков, получения смен, кассовых аппаратов и кассиров, смотрите в файле [example.php](https://github.com/vsevkassu/php-sdk/blob/master/example/example.php)