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
 *  Payment.php
 *  Объект представляющий параметры платежа в кассовом чеке
 */

namespace vsevkassu\sdk;

class Payment extends BaseObject {

    /**
     * Итоговая сумма чека
     * обязательно
     * @var float
     */
    public $full_sum;

    /**
     * сумма наличной оплатой
     * обязательно
     * @var float
     */
    public $sum_nal;

    /**
     * сумма безналичной оплатой (электронно)
     * обязательно
     * @var float
     */
    public $sum_bn;

    /**
     * сумма предоплаты
     * @var float
     */
    public $sum_prepaid;

    /**
     * сумма оплаты в кредит
     * @var float
     */
    public $sum_postpaid;

    /**
     * сумма оплаты встречным представлением
     * @var float
     */
    public $sum_credit;

    /**
     * общая сумма без НДС
     * @var float
     */
    public $wonds;

    /**
     * сумма НДС по ставке 20%
     * @var float
     */
    public $nds20;

    /**
     * сумма НДС по ставке 10%
     * @var float
     */
    public $nds10;

    /**
     * сумма НДС по ставке 20/120
     * @var float
     */
    public $nds120;

    /**
     * сумма НДС по ставке 10/110
     * @var float
     */
    public $nds110;

    /**
     * общая сумма с НДС 0
     * @var float
     */
    public $nds0;

    /**
     * создание объекта из ассоциативного массива
     * возвращает экземпляр объекта
     *
     * @param $data array
     * @return Payment
     */
    public static function fromData($data)
    {
        $instance = new Payment();
        return $instance->addData($data);
    }
}