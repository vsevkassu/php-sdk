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
 *  Shift.php
 *  Объект представляющий смену
 */

namespace vsevkassu\sdk;


class Shift extends BaseObject
{

    /**
     * @var int системный идентификатор смены
     */
    public $id;

    /**
     * @var int системный идентификатор кассы
     */
    public $salepoint_id;

    /**
     * @var int порядковый номер смены
     */
    public $number;

    /**
     * @var string состояние смены, открыта, закрыта или просрочена
     */
    public $state;

    /**
     * @var string дата окончания срока смены
     */
    public $expired_at;

    /**
     * @var string дата закрытия смены
     */
    public $closed_at;

    /**
     * @var string день, на который открыта смена
     */
    public $opened_day;

    /**
     * Параметры смены.
     * В разработке.
     */
    /*public $cash;
    public $incom_count;
    public $incom_sum;
    public $sell_count;
    public $sell_sum;
    public $buy_count;
    public $buy_sum;
    public $sellret_count;
    public $sellret_sum;
    public $buyret_count;
    public $buyret_sum;*/

    /**
     * @var string наименование организации
     */
    public $organization_name;

    /**
     * @var string ИНН организации
     */
    public $organization_inn;

    /**
     * @var string серийный номер кассы
     */
    public $salepoint_serial;

    /**
     * @var string регистрационный номер кассы
     */
    public $salepoint_regnum;

    /**
     * @var string серийный номер фискального накопителя
     */
    public $fs_serial;

    /**
     * @var string тип смены для документа открытия или закрытия
     */
    public $type;

    /**
     * @var int системны идентификатор кассира для документа открытия
     */
    public $cashier_id;

    /**
     * Константы для $state
     * SHIFT_STATE_OPENED - смена открыта
     * SHIFT_STATE_CLOSED - смена закрыта
     * SHIFT_STATE_EXPIRED - смена просрочена
     */
    const SHIFT_STATE_OPENED = 'opened';
    const SHIFT_STATE_CLOSED = 'closed';
    const SHIFT_STATE_EXPIRED = 'expired';

    /**
     * Константы для $type
     * SHIFT_TYPE_OPEN - открытие смены
     * SHIFT_TYPE_CLOSE - закрытие смены
     */
    const SHIFT_TYPE_OPEN = 'open';
    const SHIFT_TYPE_CLOSE = 'close';

    /**
     * создание объекта из ассоциативного массива
     * возвращает экземпляр объекта
     *
     * @param $data array
     * @return $this Shift
     */
    public static function fromData($data)
    {
        $instance = new Shift();
        return $instance->addData($data);
    }

    /**
     * Создание документа открытия или закрытия смены
     *
     * @param $type string
     * @param $salepoint_id int
     * @param bool $cashier_id int
     * @return Shift
     */
    public static function createShift($type, $salepoint_id, $cashier_id = false)
    {
        $instance = new Shift();
        $instance->type = $type;
        $instance->salepoint_id = $salepoint_id;
        if($cashier_id)
            $instance->cashier_id = $cashier_id;
        return $instance;
    }

}