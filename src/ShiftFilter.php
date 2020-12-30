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
 *  ShiftFilter.php
 *  Объект представляющий фильтр для поиска смен
 */


namespace vsevkassu\sdk;

class ShiftFilter extends BaseFilter {

    /**
     * Серийный номер фискального накопителя
     *
     * @var string
     */
    public $fs_id;

    /**
     * Номер смены равен
     *
     * @var int
     */
    public $number;

    /**
     * Состояние смены
     * константа Shift::SHIFT_STATE_
     *
     * @var string
     */
    public $state;

    /**
     * Дата, на которую открыта смена. Формат YYYY-MM-DD
     *
     * @var string
     */
    public $opened_day;

    /**
     * Смены, которые открыты позже даты.
     * Формат YYYY-MM-DD HH:MM:SS
     *
     * @var string
     */
    public $opened_day_from;

    /**
     * Смены, которые открыты ранее даты.
     * Формат YYYY-MM-DD HH:MM:SS
     *
     * @var string
     */
    public $opened_day_to;

    /**
     * Номер смены более или равен
     *
     * @var int
     */
    public $number_from;

    /**
     * Номер смены менее или равен
     *
     * @var int
     */
    public $number_to;

    /**
     * Смещение. По умолчанию выводится 50 записей
     * Сортировка по opened_day desc
     *
     * @var int
     */
    public $offset;

    /**
     * Создание фильтра из ассоциативного массива
     * Возвращает экземпляр объекта
     *
     * @param $data
     * @return ShiftFilter
     */
    public static function fromData($data)
    {
        $instance = new ShiftFilter();
        return $instance->addData($data);
    }

}