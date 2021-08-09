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

## Другие задачи по работе с чеками

Другие задачи по работе со списком чеков, получения смен, кассовых аппаратов и кассиров, смотрите в файле [example.php](https://github.com/vsevkassu/php-sdk/blob/master/example/example.php)

## Работа с заказами

###Создание нового заказа

```php

$order = new \vsevkassu\sdk\Order();

/* укажем кассу для которой создается заказ и контакты покупателя */
$order->salepoint_id = 1;
$order->client_name = "Петя";
$order->client_contact = "petya@mail.ru";
/* сохраним заказ */
$order = $api->saveOrder($order);

```

Результат

```
object(vsevkassu\sdk\Order)#4 (16) {
    ["id"] => int(23)
    ["org_id"] => int(12)
    ["salepoint_id"] => int(20)
    ["ext_id"] => string(17) "vvk_6110e285f0700"
    ["order_status"] => int(1)
    ["created_at"] => string(19) "2021-08-09 11:08:37"
    ["client_contact"] => string(13) "petya@mail.ru"
    ["client_name"] => string(8) "Петя"
    ["order_sum"] => string(4) "0.00"
    ["order_paid"] => string(4) "0.00"
    ["advance_paid"] => string(4) "0.00"
    ["shipped"] => int(0)
    ["products"]=>
        array(0) {
        }
    ["journal"]=> 
        array(1) {
            [0] => object(vsevkassu\sdk\OrderJournal)#5 (3) {
                    ["action_date"] => string(19) "2021-08-09 11:08:37"
                    ["action_name"]=>string(23) "Создан заказ"
                    ["description"]=>string(34) "Создан новый заказ"
                   }
        }
    ["payments"]=>
        array(0) {
        }
    ["order_status_name"] => string(12) "Создан"
}
```

###Добавление товаров в заказ

Для добавления в заказ товаров, их нужно либо создать заранее, и передать product_id, либо передать 
в объекте при добавлении в заказ, в этом случае товар создастся автоматически.
Если оплатить заказ без товаров, то создастся чек аванса, затем в заказ добавляются товары, и при прохождении  
окончательной оплаты создается чек с зачетом аванса.

```php

/* Добавим существующий товар в заказ */
$order = $api->addOrderProduct($order, \vsevkassu\sdk\OrderProduct::fromData([
    'product_id' => 12,
    'quantity' => 1,
]));

/* Добавим новый товар в заказ */
$order = $api->addOrderProduct($order, \vsevkassu\sdk\OrderProduct::fromData([
    'product_name' => 'Смартфон APPLE iPhone 12 64Gb',
    'price' => 69790,
    'vat_type' => Item::NDS_TYPE_WO,
    'quantity' => 1,
]));
/* Изменим стоимость первого товара в заказе */
$orderProduct = $order->products[0];
$orderProduct->price = 500;
$order = $api->changeOrderProduct($order, $orderProduct);

/* Удалим первый товар в заказе */
$orderProduct = $order->products[0];
$order = $api->deleteOrderProduct($order, $orderProduct);

```

###добавление оплат к заказу
 Можно проводить оплату сразу при добавлении, а можно добавить несколько запланированных (например частичная предоплата 
 и полная оплата) и проводить их по мере фактических оплат. Также, одна из оплат должна содержать флаг отгрузки, 
 означающий момент передачи товара покупателю. Если отгрузка проводится без оплаты, то необходимо создать оплату с суммой 
 0 и флагом отгрузки. Это к примеру если Вы берете 100% предоплату за товар, а отгружаете его позже.
 
```php
/* добавим предоплату к заказу 10000 руб наличными и сразу ее проведем, товар еще не отдан, поэтому shipped=0 */
$order = $api->addOrderPayment($order, \vsevkassu\sdk\OrderPayment::fromData([
    'sum_nal' => 10000,
    'accepted' => 1,
    'shipped' => 0
]));

/* добавим запланированную окончательную оплату 59790 безналичной оплатой с отгрузкой товара */
$payment = new \vsevkassu\sdk\OrderPayment();
$payment->sum_bn = 59790;
$payment->shipped = 1;
$order = $api->addOrderPayment($order, $payment);

/* при получении оплаты и отгрузки товара проведем запланированную оплату */
$payment = $order->payments[1];
$payment->accepted = 1;
$order = $api->changeOrderPayment($order, $payment);

``` 

Итоговый объект заказа будет выглядеть следующим образом (сформированные чеки для каждой оплаты находятся
в поле receipt объекта OrderPayment):

```

object(vsevkassu\sdk\Order)#21 (16) {
  ["id"]=>
  int(24)
  ["org_id"]=>
  int(12)
  ["salepoint_id"]=>
  int(20)
  ["ext_id"]=>
  string(17) "vvk_6110ecbc498f1"
  ["order_status"]=>
  int(3)
  ["created_at"]=>
  string(19) "2021-08-09 11:52:12"
  ["client_contact"]=>
  string(13) "petya@mail.ru"
  ["client_name"]=>
  string(8) "Петя"
  ["order_sum"]=>
  string(8) "69790.00"
  ["order_paid"]=>
  string(8) "69790.00"
  ["advance_paid"]=>
  string(4) "0.00"
  ["shipped"]=>
  int(1)
  ["products"]=>
  array(1) {
    [0]=>
    object(vsevkassu\sdk\OrderProduct)#22 (10) {
      ["id"]=>
      int(35)
      ["product_id"]=>
      int(15)
      ["product_ext_id"]=>
      string(17) "vvk_6110ed1a61022"
      ["product_name"]=>
      string(37) "Смартфон APPLE iPhone 12 64Gb"
      ["price"]=>
      string(8) "69790.00"
      ["quantity"]=>
      string(5) "1.000"
      ["sum"]=>
      string(8) "69790.00"
      ["vat_type"]=>
      int(6)
      ["payed"]=>
      string(8) "69790.00"
      ["returned"]=>
      string(4) "0.00"
    }
  }
  ["journal"]=>
  array(12) {
    [0]=>
    object(vsevkassu\sdk\OrderJournal)#23 (3) {
      ["action_date"]=>
      string(19) "2021-08-09 11:52:12"
      ["action_name"]=>
      string(23) "Создан заказ"
      ["description"]=>
      string(34) "Создан новый заказ"
    }
    [1]=>
    object(vsevkassu\sdk\OrderJournal)#24 (3) {
      ["action_date"]=>
      string(19) "2021-08-09 11:53:01"
      ["action_name"]=>
      string(27) "Добавлен товар"
      ["description"]=>
      string(58) "Добавлен товар: Монитор (Товар 1)"
    }
    [2]=>
    object(vsevkassu\sdk\OrderJournal)#25 (3) {
      ["action_date"]=>
      string(19) "2021-08-09 11:53:01"
      ["action_name"]=>
      string(25) "Изменен заказ"
      ["description"]=>
      string(62) "Сумма заказа изменена с 0.00 на 16459.00"
    }
    [3]=>
    object(vsevkassu\sdk\OrderJournal)#26 (3) {
      ["action_date"]=>
      string(19) "2021-08-09 11:53:46"
      ["action_name"]=>
      string(27) "Добавлен товар"
      ["description"]=>
      string(66) "Добавлен товар: Смартфон APPLE iPhone 12 64Gb"
    }
    [4]=>
    object(vsevkassu\sdk\OrderJournal)#27 (3) {
      ["action_date"]=>
      string(19) "2021-08-09 11:53:46"
      ["action_name"]=>
      string(25) "Изменен заказ"
      ["description"]=>
      string(66) "Сумма заказа изменена с 16459.00 на 86249.00"
    }
    [5]=>
    object(vsevkassu\sdk\OrderJournal)#28 (3) {
      ["action_date"]=>
      string(19) "2021-08-09 11:54:29"
      ["action_name"]=>
      string(25) "Изменен товар"
      ["description"]=>
      string(56) "Изменен товар: Монитор (Товар 1)"
    }
    [6]=>
    object(vsevkassu\sdk\OrderJournal)#29 (3) {
      ["action_date"]=>
      string(19) "2021-08-09 11:54:29"
      ["action_name"]=>
      string(25) "Изменен заказ"
      ["description"]=>
      string(66) "Сумма заказа изменена с 86249.00 на 70290.00"
    }
    [7]=>
    object(vsevkassu\sdk\OrderJournal)#30 (3) {
      ["action_date"]=>
      string(19) "2021-08-09 11:54:57"
      ["action_name"]=>
      string(23) "Удален товар"
      ["description"]=>
      string(54) "Удален товар: Монитор (Товар 1)"
    }
    [8]=>
    object(vsevkassu\sdk\OrderJournal)#31 (3) {
      ["action_date"]=>
      string(19) "2021-08-09 11:54:57"
      ["action_name"]=>
      string(25) "Изменен заказ"
      ["description"]=>
      string(66) "Сумма заказа изменена с 70290.00 на 69790.00"
    }
    [9]=>
    object(vsevkassu\sdk\OrderJournal)#32 (3) {
      ["action_date"]=>
      string(19) "2021-08-09 11:55:38"
      ["action_name"]=>
      string(31) "Добавлена оплата"
      ["description"]=>
      string(62) "Добавлена предоплата в сумме 10000.00"
    }
    [10]=>
    object(vsevkassu\sdk\OrderJournal)#33 (3) {
      ["action_date"]=>
      string(19) "2021-08-09 11:57:38"
      ["action_name"]=>
      string(31) "Добавлена оплата"
      ["description"]=>
      string(54) "Добавлена оплата в сумме 59790.00"
    }
    [11]=>
    object(vsevkassu\sdk\OrderJournal)#34 (3) {
      ["action_date"]=>
      string(19) "2021-08-09 11:57:38"
      ["action_name"]=>
      string(27) "Заказ отгружен"
      ["description"]=>
      string(48) "Произведена выдача заказа"
    }
  }
  ["payments"]=>
  array(2) {
    [0]=>
    object(vsevkassu\sdk\OrderPayment)#35 (8) {
      ["id"]=>
      int(24)
      ["order_id"]=>
      int(24)
      ["pay_accepted"]=>
      int(1)
      ["sum_nal"]=>
      string(8) "10000.00"
      ["sum_bn"]=>
      string(4) "0.00"
      ["full_sum"]=>
      string(8) "10000.00"
      ["shipped"]=>
      int(0)
      ["receipt"]=>
      object(vsevkassu\sdk\Receipt)#36 (31) {
        ["id"]=>
        int(191)
        ["salepoint_id"]=>
        int(20)
        ["external_id"]=>
        string(17) "vvk_6110ed8a06120"
        ["created_at"]=>
        string(19) "2021-08-09 11:55:38"
        ["status"]=>
        string(4) "wait"
        ["error"]=>
        NULL
        ["is_print"]=>
        int(1)
        ["type"]=>
        string(1) "1"
        ["type_name"]=>
        string(12) "ПРИХОД"
        ["organization_id"]=>
        int(12)
        ["organization_name"]=>
        string(32) "ИП Иванов Иван Иванович"
        ["organization_inn"]=>
        string(12) "777777777777"
        ["cashier_name"]=>
        string(0) ""
        ["cashier_inn"]=>
        string(0) ""
        ["salepoint_place"]=>
        string(12) "vsevkassu.ru"
        ["salepoint_address"]=>
        string(66) ""
        ["kkt_serial"]=>
        string(14) "11111111111111"
        ["kkt_regnum"]=>
        string(16) "0000000000000000"
        ["kkt_fs"]=>
        string(16) "9999999999999111"
        ["kkt_sender_email"]=>
        string(22) "service@vsevkassu.ru"
        ["fns_site"]=>
        string(8) "nalog.ru"
        ["fiscal_date"]=>
        string(0) ""
        ["shift"]=>
        string(0) ""
        ["doc_number"]=>
        string(0) ""
        ["fd_number"]=>
        string(0) ""
        ["sign"]=>
        string(0) ""
        ["buyer"]=>
        object(vsevkassu\sdk\Buyer)#37 (3) {
          ["contact"]=>
          string(13) "petya@mail.ru"
          ["name"]=>
          string(8) "Петя"
          ["inn"]=>
          string(0) ""
        }
        ["items"]=>
        array(1) {
          [0]=>
          object(vsevkassu\sdk\Item)#38 (13) {
            ["item_type"]=>
            int(10)
            ["item_type_name"]=>
            string(12) "ПЛАТЕЖ"
            ["name"]=>
            string(37) "Смартфон APPLE iPhone 12 64Gb"
            ["quantity"]=>
            int(1)
            ["quant"]=>
            string(4) "Шт"
            ["pay_type"]=>
            int(2)
            ["pay_type_name"]=>
            string(20) "ПРЕДОПЛАТА"
            ["price"]=>
            int(10000)
            ["sum"]=>
            int(10000)
            ["price_nds"]=>
            int(0)
            ["nds_type"]=>
            int(6)
            ["nds_type_name"]=>
            string(13) "БЕЗ НДС"
            ["nds_sum"]=>
            int(0)
          }
        }
        ["payment"]=>
        object(vsevkassu\sdk\Payment)#39 (12) {
          ["full_sum"]=>
          int(10000)
          ["sum_nal"]=>
          int(10000)
          ["sum_bn"]=>
          int(0)
          ["sum_prepaid"]=>
          int(0)
          ["sum_postpaid"]=>
          int(0)
          ["sum_credit"]=>
          int(0)
          ["wonds"]=>
          int(10000)
          ["nds20"]=>
          int(0)
          ["nds10"]=>
          int(0)
          ["nds120"]=>
          int(0)
          ["nds110"]=>
          int(0)
          ["nds0"]=>
          int(0)
        }
        ["tax_name"]=>
        string(19) "УСН ДОХОДЫ"
        ["tax"]=>
        string(1) "1"
      }
    }
    [1]=>
    object(vsevkassu\sdk\OrderPayment)#40 (8) {
      ["id"]=>
      int(25)
      ["order_id"]=>
      int(24)
      ["pay_accepted"]=>
      int(1)
      ["sum_nal"]=>
      string(4) "0.00"
      ["sum_bn"]=>
      string(8) "59790.00"
      ["full_sum"]=>
      string(8) "59790.00"
      ["shipped"]=>
      int(1)
      ["receipt"]=>
      object(vsevkassu\sdk\Receipt)#41 (31) {
        ["id"]=>
        int(192)
        ["salepoint_id"]=>
        int(20)
        ["external_id"]=>
        string(17) "vvk_6110ee029c571"
        ["created_at"]=>
        string(19) "2021-08-09 11:57:38"
        ["status"]=>
        string(4) "wait"
        ["error"]=>
        NULL
        ["is_print"]=>
        int(1)
        ["type"]=>
        string(1) "1"
        ["type_name"]=>
        string(12) "ПРИХОД"
        ["organization_id"]=>
        int(12)
        ["organization_name"]=>
        string(32) "ИП Иванов Иван Иванович"
        ["organization_inn"]=>
        string(12) "777777777777"
        ["cashier_name"]=>
        string(0) ""
        ["cashier_inn"]=>
        string(0) ""
        ["salepoint_place"]=>
        string(12) "vsevkassu.ru"
        ["salepoint_address"]=>
        string(66) ""
        ["kkt_serial"]=>
        string(14) "11111111111111"
        ["kkt_regnum"]=>
        string(16) "0000000000000000"
        ["kkt_fs"]=>
        string(16) "9999999999999111"
        ["kkt_sender_email"]=>
        string(22) "service@vsevkassu.ru"
        ["fns_site"]=>
        string(8) "nalog.ru"
        ["fiscal_date"]=>
        string(0) ""
        ["shift"]=>
        string(0) ""
        ["doc_number"]=>
        string(0) ""
        ["fd_number"]=>
        string(0) ""
        ["sign"]=>
        string(0) ""
        ["buyer"]=>
        object(vsevkassu\sdk\Buyer)#42 (3) {
          ["contact"]=>
          string(13) "petya@mail.ru"
          ["name"]=>
          string(8) "Петя"
          ["inn"]=>
          string(0) ""
        }
        ["items"]=>
        array(1) {
          [0]=>
          object(vsevkassu\sdk\Item)#43 (13) {
            ["item_type"]=>
            int(1)
            ["item_type_name"]=>
            string(10) "ТОВАР"
            ["name"]=>
            string(37) "Смартфон APPLE iPhone 12 64Gb"
            ["quantity"]=>
            int(1)
            ["quant"]=>
            string(0) ""
            ["pay_type"]=>
            int(4)
            ["pay_type_name"]=>
            string(25) "ПОЛНЫЙ РАСЧЕТ"
            ["price"]=>
            int(69790)
            ["sum"]=>
            int(69790)
            ["price_nds"]=>
            int(0)
            ["nds_type"]=>
            int(6)
            ["nds_type_name"]=>
            string(13) "БЕЗ НДС"
            ["nds_sum"]=>
            int(0)
          }
        }
        ["payment"]=>
        object(vsevkassu\sdk\Payment)#44 (12) {
          ["full_sum"]=>
          int(69790)
          ["sum_nal"]=>
          int(0)
          ["sum_bn"]=>
          int(59790)
          ["sum_prepaid"]=>
          int(10000)
          ["sum_postpaid"]=>
          int(0)
          ["sum_credit"]=>
          int(0)
          ["wonds"]=>
          int(69790)
          ["nds20"]=>
          int(0)
          ["nds10"]=>
          int(0)
          ["nds120"]=>
          int(0)
          ["nds110"]=>
          int(0)
          ["nds0"]=>
          int(0)
        }
        ["tax_name"]=>
        string(19) "УСН ДОХОДЫ"
        ["tax"]=>
        string(1) "1"
      }
    }
  }
  ["order_status_name"]=>
  string(33) "Оплачен полностью"
}
```

## Другие задачи по работе с заказами

Другие задачи по работе с заказами смотрите в файле [orders_example.php](https://github.com/vsevkassu/php-sdk/blob/master/example/orders_example.php)
