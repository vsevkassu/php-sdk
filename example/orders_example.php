<?php
/**
 *  Vsevkassu Software Development Toolkit
 *  Copyright (c) 2021 vsevkassu.ru
 *
 *  ================== Все в кассу ==================
 *    Сервис «Всё в кассу» предназначен для удобной
 *    интеграции онлайн-касс с интернет-магазином в
 *            полном соответствии с 54-ФЗ
 *  =================================================
 *
 *  orders_example.php
 *  Примеры работы с API заказов при помощи SDK
 */

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
$api = new \vsevkassu\sdk\VsevkassuAPI();
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
 * Создадим новый заказ
 */
$order = new \vsevkassu\sdk\Order();

/* укажем кассу для которой создается заказ и контакты покупателя */
$order->salepoint_id = 1;
$order->client_name = "Петя";
$order->client_contact = "petya@mail.ru";
/* сохраним заказ */
$order = $api->saveOrder($order);

/**
 * Для добавления в заказ товаров, их нужно либо создать заранее, и передать product_id
 * либо передать в объекте при добавлении в заказ, в этом случае
 * товар создастся автоматически.
 * Если оплатить заказ без товаров, то создастся чек аванса
 * Затем в заказ добавляются товары, и при прохождении  окончательной оплаты создается чек с зачетом аванса
 */

/* Добавим существующий товар в заказ */
$order = $api->addOrderProduct($order, \vsevkassu\sdk\OrderProduct::fromData([
    'product_id' => 12,
    'quantity' => 1,
]));

/* Добавим новый товар в заказ */
$order = $api->addOrderProduct($order, \vsevkassu\sdk\OrderProduct::fromData([
    'product_name' => 'Смартфон APPLE iPhone 12 64Gb',
    'price' => 69790,
    'vat_type' => \vsevkassu\sdk\Item::NDS_TYPE_WO,
    'quantity' => 1,
]));
/* Изменим стоимость первого товара в заказе */
$orderProduct = $order->products[0];
$orderProduct->price = 500;
$order = $api->changeOrderProduct($order, $orderProduct);

/* Удалим первый товар в заказе */
$orderProduct = $order->products[0];
$order = $api->deleteOrderProduct($order, $orderProduct);


/**
 * добавление оплат к заказу
 * можно проводить сразу при добавлении, а можно добавить несколько запланированных
 * (например частичная предоплата и полная оплата) и проводить их по мере фактических оплат.
 * Также, одна из оплат должна содержать флаг отгрузки, означающий момент передачи товара покупателю.
 * Если отгрузка проводится без оплаты, то необходимо создать оплату с суммой 0 и флагом отгрузки.
 * Это к примеру если Вы берете 100% предоплату за товар, а отгружаете его позже.
 */

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