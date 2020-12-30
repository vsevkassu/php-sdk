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
 *  Cashier.php
 *  Объект представляющий кассира
 */


namespace vsevkassu\sdk;

class Cashier extends BaseObject {

    /**
     * Системный номер кассира
     * @var int
     */
    public $id;

    /**
     * Системный номер организации
     * @var int
     */
    public $org_id;

    /**
     * Имя кассира
     * @var string
     */
    public $name;

    /**
     * ИНН Кассира
     * @var string
     */
    public $inn;

    /**
     * создание объекта из ассоциативного массива
     * возвращает экземпляр объекта
     *
     * @param $data array
     * @return Cashier
     */
    public static function fromData($data)
    {
        $instance = new Cashier();
        return $instance->addData($data);
    }

}