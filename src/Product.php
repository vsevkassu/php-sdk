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
 *  Product.php
 *  Объект представляющий продукт в функционале работы с заказами
 */

namespace vsevkassu\sdk;

class Product extends BaseObject {

    /**
     * Системный идентификатор продукта
     * @var int
     */
    public $id = false;

    /**
     * Системный идентификатор организации
     * @var int
     */
    public $org_id;

    /**
     * Артикул, уникальный внешний идентификатор продукта
     * @var string
     */
    public $ext_id;

    /**
     * Наименование продукта
     * @var string
     */
    public $prod_name;

    /**
     * Единица измерения продукта
     * @var string
     */
    public $prod_quant;

    /**
     * Тип продукта, константа Item::ITEM_TYPE_
     * @var int
     */
    public $prod_type;

    /**
     * Цена продукта
     * @var float
     */
    public $prod_price;

    /**
     * НДС для продукта, константа Item::NDS_TYPE_
     * @var int
     */
    public $prod_vat;

    /**
     * признак удаления продукта
     * @var string
     */
    public $archive;

    /**
     * создание объекта из ассоциативного массива
     * возвращает экземпляр объекта
     *
     * @param $data array
     * @return Product
     */
    public static function fromData($data)
    {
        $instance = new Product();
        return $instance->addData($data);
    }


}