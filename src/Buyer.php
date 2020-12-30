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
 *  Buyer.php
 *  Объект представляющий покупателя в кассовом чеке
 */


namespace vsevkassu\sdk;

class Buyer extends BaseObject {

    /**
     * E-Mail или телефон покупателя для отправки электронного чека
     * @var string
     */
    public $contact;

    /**
     * Имя покупателя
     * @var string
     */
    public $name;

    /**
     * ИНН покупателя
     * @var string
     */
    public $inn;

    /**
     * создание объекта из ассоциативного массива
     * возвращает экземпляр объекта
     *
     * @param $data array
     * @return Buyer
     */
    public static function fromData($data)
    {
        $instance = new Buyer();
        return $instance->addData($data);
    }

}