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
 *  ReceiptFilter.php
 *  Объект представляющий фильтр для поиска чеков
 */


namespace vsevkassu\sdk;

class ReceiptFilter extends BaseFilter {

    /**
     * Системный номер организации
     * @var int
     */
    public $organization_id;

    /**
     * Системный номер кассовго аппарата
     * @var int
     */
    public $salepoint_id;

    /**
     * Уникальный номер в системе пользователя
     * @var string
     */
    public $external_id;

    /**
     * Статус, константа Receipt::STATUS_
     * @var string
     */
    public $status;

    /**
     * Минимальная дата чека, формат YYYY-MM-DD HH:MM:SS
     * @var string
     */
    public $created_at_from;

    /**
     * Максимальная дата чека, формат YYYY-MM-DD HH:MM:SS
     * @var string
     */
    public $created_at_to;

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
     * @return ReceiptFilter
     */
    public static function fromData($data)
    {
        $instance = new ReceiptFilter();
        return $instance->addData($data);
    }



}