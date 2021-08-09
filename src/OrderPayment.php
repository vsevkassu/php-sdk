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
 *  OrderPayment.php
 *  Объект представляющий оплату в заказе
 */

namespace vsevkassu\sdk;

class OrderPayment extends BaseObject {

    /**
     * Системный идентификатор оплаты в заказе
     * @var int
     */
    public $id = false;

    /**
     * Системный идентификатор заказа
     * @var int
     */
    public $order_id;

    /**
     * Флаг проведения оплаты, 0 - оплата не проведена, 1 - оплата проведена
     * @var int
     */
    public $pay_accepted;

    /**
     * Сумма оплаты наличными
     * @var float
     */
    public $sum_nal;

    /**
     * Сумма оплаты электронно
     * @var float
     */
    public $sum_bn;

    /**
     * Полная сумма оплаты
     * @var float
     */
    public $full_sum;

    /**
     * Флаг отгрузки заказа, 0 - не отгружен, 1 - отгружен
     * @var int
     */
    public $shipped;

    /**
     * Объект чека по оплате
     * @var Receipt
     */
    public $receipt;

    /**
     * создание объекта из ассоциативного массива
     * возвращает экземпляр объекта
     *
     * @param $data array
     * @return OrderPayment
     */
    public static function fromData($data)
    {
        $instance = new OrderPayment();

        if(is_string($data))
            $data = json_decode($data, true);

        if($data) {
            foreach ($data as $name => $value) {
                switch ($name) {
                    case 'receipt' :
                        $instance->receipt = Receipt::fromData($value);
                        break;
                    default :
                        $instance->$name = $value;
                }
            }
        }

        return $instance;
    }

}