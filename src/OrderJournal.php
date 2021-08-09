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
 *  OrderJournal.php
 *  Объект представляющий строку в журнале изменений по заказу
 */

namespace vsevkassu\sdk;

class OrderJournal extends BaseObject {

    /**
     * Дата и время изменения
     * @var string
     */
    public $action_date;

    /**
     * Тип изменения по заказу
     * @var string
     */
    public $action_name;

    /**
     * Описание изменения по заказу
     * @var string
     */
    public $description;

    /**
     * создание объекта из ассоциативного массива
     * возвращает экземпляр объекта
     *
     * @param $data array
     * @return OrderJournal
     */
    public static function fromData($data)
    {
        $instance = new OrderJournal();
        return $instance->addData($data);
    }


}