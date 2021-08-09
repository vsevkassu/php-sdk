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
 *  Order.php
 *  Объект представляющий заказ в функционале работы с заказами
 */

namespace vsevkassu\sdk;

class Order extends BaseObject {

    /**
     * Системный идентификатор заказа
     * @var int
     */
    public $id = false;

    /**
     * Системный идентификатор организации
     * @var int
     */
    public $org_id;

    /**
     * Системный идентификатор кассового аппарата
     * @var int
     */
    public $salepoint_id;

    /**
     * Артикул, уникальный внешний идентификатор заказа
     * @var string
     */
    public $ext_id;

    /**
     * Статус заказа, константа ORDER_STATUS_
     * @var int
     */
    public $order_status;

    /**
     * Дата создания заказа, Дата в формате YYYY-MM-DD HH:II:SS
     * @var string
     */
    public $created_at;

    /**
     * E-Mail или телефон клиента
     * @var string
     */
    public $client_contact;

    /**
     * Имя клиента
     * @var string
     */
    public $client_name;

    /**
     * Сумма по заказу
     * @var float
     */
    public $order_sum;

    /**
     * Сумма принятых оплат по заказу
     * @var float
     */
    public $order_paid;

    /**
     * Сумма аванса по заказу
     * @var float
     */
    public $advance_paid;

    /**
     * Флаг отгрузки заказа, 0 - не отгружен, 1 - отгружен
     * @var int
     */
    public $shipped;

    /**
     * Продукты в заказе
     * @var array OrderProduct
     */
    public $products = [];

    /**
     * Журнал заказа
     * @var array OrderJournal
     */
    public $journal = [];

    /**
     * Оплаты по заказу
     * @var array OrderPayment
     */
    public $payments = [];


    /**
     * статус заказа
     * ORDER_STATUS_NEW - заказ создан
     * ORDER_STATUS_PAYED_PARTIAL - заказ оплачен частично
     * ORDER_STATUS_PAYED - заказ оплачен полностью
     * ORDER_STATUS_PAYMENT_RETURN - оплата по заказу возвращена
     * ORDER_STATUS_ADVANCE - получен аванс по заказу
     */
    const ORDER_STATUS_NEW = 1;
    const ORDER_STATUS_PAYED_PARTIAL = 2;
    const ORDER_STATUS_PAYED = 3;
    const ORDER_STATUS_PAYMENT_RETURN = 4;
    const ORDER_STATUS_ADVANCE = 5;



    /**
     * создание объекта из ассоциативного массива
     * возвращает экземпляр объекта
     *
     * @param $data array
     * @return Order
     */
    public static function fromData($data)
    {
        $instance = new Order();

        if(is_string($data))
            $data = json_decode($data, true);

        if($data) {
            foreach ($data as $name => $value) {
                switch ($name) {
                    case 'products' :
                        foreach ($value as $item){
                            $instance->products[] = OrderProduct::fromData($item);
                        }
                        break;
                    case 'journal' :
                        foreach ($value as $item){
                            $instance->journal[] = OrderJournal::fromData($item);
                        }
                        break;
                    case 'payments' :
                        foreach ($value as $item){
                            $instance->payments[] = OrderPayment::fromData($item);
                        }
                        break;
                    default :
                        $instance->$name = $value;
                }
            }
        }

        return $instance;
    }


}