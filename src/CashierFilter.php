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
 *  CashierFilter.php
 *  Объект представляющий фильтр для поиска кассира
 */


namespace vsevkassu\sdk;

class CashierFilter extends BaseFilter {

    /**
     * Поиск по системному идентификатору организации
     * @var int
     */
    public $org_id;

    /**
     * Поиск по частичному совпадению имени кассира
     * @var string
     */
    public $name;

    /**
     * Смещение.
     * Выдается максимум 50 записей, сортировка по id asc
     * @var int
     */
    public $offset;

    /**
     * Создание фильтра из ассоциативного массива
     * Возвращает экземпляр объекта
     *
     * @param $data
     * @return CashierFilter
     */
    public static function fromData($data)
    {
        $instance = new CashierFilter();
        return $instance->addData($data);
    }

}