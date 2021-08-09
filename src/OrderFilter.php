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
 *  OrderFilter.php
 *  Объект представляющий фильтр для поиска товаров
 */


namespace vsevkassu\sdk;

class OrderFilter extends BaseFilter {

    /**
     * Системный номер кассы
     * @var int
     */
    public $salepoint_id;

    /**
     * Внешний идентификатор заказа
     * @var string
     */
    public $ext_id;

    /**
     * Статус заказа, константа Order::ORDER_STATUS_
     * @var int
     */
    public $order_status;

    /**
     * Контакт клиента (поиск по полному совпадению)
     * @var string
     */
    public $client_contact;

    /**
     * Имя клиента (поиск по поолному совпадению)
     * @var string
     */
    public $client_name;

    /**
     * Минимальная дата создания заказа, формат YYYY-MM-DD HH:MM:SS
     * @var string
     */
    public $created_at_from;

    /**
     * Максимальная дата создания заказа, формат YYYY-MM-DD HH:MM:SS
     * @var string
     */
    public $created_at_to;

    /**
     * Признак отгрузки заказа, 1 или 0
     * @var int
     */
    public $shipped;

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
     * @return OrderFilter
     */
    public static function fromData($data)
    {
        $instance = new OrderFilter();
        return $instance->addData($data);
    }

}