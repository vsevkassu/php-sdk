<?php
/**
 *  Vsevkassu Software Development Toolkit
 *  Copyright (c) 2020 vsevkassu.ru
 *
 *  ================== Все в кассу ==================
 *    Сервис «Всё в кассу» предназначен для удобной
 *    интеграции онлайн-касс с интернет-магазином в
 *            полном соответствии с 54-ФЗ
 *  =================================================
 *
 *  example.php
 *  Примеры работы с API при помощи SDK
 */

use vsevkassu\sdk\VsevkassuAPI;
use vsevkassu\sdk\ShiftFilter;
use vsevkassu\sdk\CashboxFilter;
use vsevkassu\sdk\CashierFilter;
use vsevkassu\sdk\ReceiptFilter;
use vsevkassu\sdk\Receipt;
use vsevkassu\sdk\Item;
use vsevkassu\sdk\Payment;

require '../autoloader.php';

/**
 * Инициализация класса, установка логина и пароля
 * Второй способ авторизации - использовать токен напрямую
 * При использовании токена метод auth() вызывать не нужно
 *
 * $api = new VsevkassuAPI();
 * $api->token = 'xxxxxxxxxxxxxxxxxxxxxxxxx';
 *
 */
$api = new VsevkassuAPI();
$api->login = 'my@email.ru';
$api->password = 'mypassword';

/**
 * Функция авторизации
 *
 * при неверных данных выбрасывает исключение
 * Для перехвата используйте try
 */
$api->auth();


/**
 * Информация о версии API
 */
echo 'ApiVersion: ' . $api->getVer();


/**
 * получение списка смен с фильтрацией
 * описание параметров фильтра в файле ShiftFilter.php
 * Возвращает массив объектов vsevkassu\sdk\Shift
 */
$Shifts = $api->findShifts(ShiftFilter::fromData([
    'number_from' => 7
]));

/**
 * получение смены по ID
 * Возвращает объект vsevkassu\sdk\Shift
 */
$Shift = $api->findShift(133);


/**
 * открытие смены на кассе. Вторым параметром можно указать Id кассира
 * Если не указывать кассира, то откроется смена с кассиром по умолчанию
 * который установлен в системе для указанного кассового аппарата
 * Возвращает идентификатор документа
 */
$shift_id = $api->openShift(20);

/**
 * закрытие смены на кассе
 * Возвращает идентификатор документа
 */
$shift_id = $api->closeShift(20);

/**
 * Получение списка кассовых аппаратов
 * Описание параметров фильтра в CashboxFilter.php
 * Возвращает список объектов vsevkassu\sdk\Cashbox
 */
$Cashboxes = $api->findCashboxes(CashboxFilter::fromData([]));

/**
 * Получение сведений о кассе по системному идентификатору
 * Возвращает объект vsevkassu\sdk\Cashbox
 */
$Cashbox = $api->findCashbox(20);

/**
 * Получение списка кассиров, заведенных в системе
 * Описание параметров фильтра в CashierFilter.php
 * Возвращает массив объектов vsevkassu\sdk\Cashier
 */
$Cashiers = $api->findCashiers(CashierFilter::fromData([]));

/**
 * Получение сведений о кассире по системному идентификатору
 * Возвращает объект vsevkassu\sdk\Cashier
 */
$Cashier = $api->findCashier(10);

/**
 * Получение чеков с фильтрацией
 * Пример альтернативного создания фильтра
 * Описание параметров фильтра в ReceiptFilter.php
 * Возвращает массив объектов vsevkassu\sdk\Receipt
 */
$filter = new ReceiptFilter();
$filter->status = Receipt::STATUS_ERROR;
$filter->external_id = 'myexternal';
$Receipts = $api->findReceipts($filter);

/**
 * Получение чека по системному идентификатору
 * Возвращает объект vsevkassu\sdk\Receipt
 */
$Receipt = $api->findReceipt(100);

/**
 * Пример создания чека
 * Показано минимальное заполнение обязательных полей
 */
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

/**
 * Пример создания такого же чека через массив
 */
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
