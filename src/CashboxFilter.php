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
 *  CashboxFilter.php
 *  Объект представляющий фильтр для поиска кассовых аппаратов
 */


namespace vsevkassu\sdk;

class CashboxFilter extends BaseFilter {

    /**
     * Системный идентификатор организации
     * @var int
     */
    public $org_id;

    /**
     * Серийный номер ККТ
     * @var string
     */
    public $kkt_serial;

    /**
     * Регистрационный номер ККТ
     * @var string
     */
    public $kkt_reg_num;

    /**
     * Смещение.
     * @var int
     */
    public $offset;

    /**
     * Создание фильтра из ассоциативного массива
     * Возвращает экземпляр объекта
     *
     * @param $data
     * @return CashboxFilter
     */
    public static function fromData($data)
    {
        $instance = new CashboxFilter();
        return $instance->addData($data);
    }

}