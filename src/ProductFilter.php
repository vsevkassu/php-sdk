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
 *  ProductFilter.php
 *  Объект представляющий фильтр для поиска товаров
 */


namespace vsevkassu\sdk;

class ProductFilter extends BaseFilter {

    /**
     * Системный номер организации
     * @var int
     */
    public $org_id;

    /**
     * Внешний идентификатор продукта
     * @var string
     */
    public $ext_id;

    /**
     * Наименование продукта (поиск по частичному соответствию)
     * @var string
     */
    public $prod_name;

    /**
     * Тип продукта, константа Item::ITEM_TYPE_
     * @var int
     */
    public $prod_type;

    /**
     * Минимальная цена
     * @var float
     */
    public $prod_price_from;

    /**
     * Максимальная цена
     * @var float
     */
    public $prod_price_to;

    /**
     * Архивный (удаленный) товар
     * @var int
     */
    public $archive;

    /**
     * Смещение
     * @var int
     */
    public $offset;

    /**
     * Создание фильтра из ассоциативного массива
     * Возвращает экземпляр объекта
     *
     * @param $data
     * @return ProductFilter
     */
    public static function fromData($data)
    {
        $instance = new ProductFilter();
        return $instance->addData($data);
    }

}