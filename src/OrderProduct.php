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
 *  OrderProduct.php
 *  Объект представляющий позицию в заказе
 */

namespace vsevkassu\sdk;

class OrderProduct extends BaseObject {

    /**
     * Системный идентификатор позиции заказа
     * @var int
     */
    public $id = false;

    /**
     * Системный идентификатор продукта
     * @var int
     */
    public $product_id;

    /**
     * Артикул, уникальный внешний идентификатор продукта
     * @var string
     */
    public $product_ext_id;

    /**
     * Наименование продукта
     * @var string
     */
    public $product_name;

    /**
     * Стоимость товара в заказе
     * @var float
     */
    public $price;

    /**
     * Количество товара в заказе
     * @var float
     */
    public $quantity;

    /**
     * Сумма позиции в заказе
     * @var float
     */
    public $sum;

    /**
     * Тип НДС для указанного товара, константа Item::NDS_TYPE_
     * @var int
     */
    public $vat_type;

    /**
     * Сумма оплат по данной позиции
     * @var float
     */
    public $payed;

    /**
     * Сумма возвратов по данной позиции
     * @var float
     */
    public $returned;

    /**
     * создание объекта из ассоциативного массива
     * возвращает экземпляр объекта
     *
     * @param $data array
     * @return OrderProduct
     */
    public static function fromData($data)
    {
        $instance = new OrderProduct();
        return $instance->addData($data);
    }


}