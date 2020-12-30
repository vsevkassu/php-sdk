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
 *  Item.php
 *  Объект представляющий товар в кассовом чеке
 */


namespace vsevkassu\sdk;

class Item extends BaseObject {

    /**
     * Тип товара, константа ITEM_TYPE_
     * @var int
     */
    public $item_type;

    /**
     * Наименование типа товара
     * @var string
     */
    public $item_type_name;

    /**
     * Наименование товара, до 255 символов
     * @var string
     */
    public $name;

    /**
     * количество товара
     * @var int|float
     */
    public $quantity;

    /**
     * единица измерения
     * @var string
     */
    public $quant;

    /**
     * Тип оплаты, константа PAY_TYPE_
     * @var int
     */
    public $pay_type;

    /**
     * Наименование типа оплаты
     * @var string
     */
    public $pay_type_name;

    /**
     * Цена за единицу товара
     * @var float
     */
    public $price;

    /**
     * Сумма: цена товара умноженная на количество
     * @var float
     */
    public $sum;

    /**
     * НДС за единицу товара
     * @var float
     */
    public $price_nds;

    /**
     * Тип НДС, константа NDS_TYPE_
     * @var int
     */
    public $nds_type;

    /**
     * Наименование НДС
     * @var string
     */
    public $nds_type_name;

    /**
     * Сумма НДС = НДС за единицу товара умноженное на количество
     * @var float
     */
    public $nds_sum;

    /**
     * Тип товара.
     * В константах только основные типы, остальные также поддреживаются системой
     * ITEM_TYPE_PROD - Товар
     * ITEM_TYPE_EXCIZE_PROD - Подакцизный товар
     * ITEM_TYPE_WORK - Работа
     * ITEM_TYPE_SERVICE - Услуга
     * ITEM_TYPE_RID - Представление результатов интеллектуальной деятельности
     * ITEM_TYPE_PAYMENT - Платеж
     * ITEM_TYPE_AGENT_REWARD - Агентское вознаграждение
     * ITEM_TYPE_OTHER - Иной предмет расчета
     */
    const ITEM_TYPE_PROD = 1;
    const ITEM_TYPE_EXCIZE_PROD =2;
    const ITEM_TYPE_WORK = 3;
    const ITEM_TYPE_SERVICE = 4;
    const ITEM_TYPE_RID = 9;
    const ITEM_TYPE_PAYMENT = 10;
    const ITEM_TYPE_AGENT_REWARD = 11;
    const ITEM_TYPE_OTHER =13;

    /**
     * Тип оплаты за товар
     * PAY_TYPE_PREPAID100 - Предоплата 100%
     * PAY_TYPE_PREPAID - Предоплата
     * PAY_TYPE_ADVANCE - Аванс
     * PAY_TYPE_FULL - Полная оплата
     * PAY_TYPE_PARTIAL - частичная оплата
     * PAY_TYPE_CREDIT - кредит
     * PAY_TYPE_CREDIT_REPAYMENT - оплата кредита
     */
    const PAY_TYPE_PREPAID100 = 1;
    const PAY_TYPE_PREPAID = 2;
    const PAY_TYPE_ADVANCE = 3;
    const PAY_TYPE_FULL = 4;
    const PAY_TYPE_PARTIAL = 5;
    const PAY_TYPE_CREDIT = 6;
    const PAY_TYPE_CREDIT_REPAYMENT = 7;

    /**
     * Тип НДС
     * NDS_TYPE_20 - НДС 20%
     * NDS_TYPE_10 - НДС 10%
     * NDS_TYPE_120 - НДС 20/120
     * NDS_TYPE_110 - НДС 10/110
     * NDS_TYPE_0 - НДС 0%
     * NDS_TYPE_WO - без НДС
     */
    const NDS_TYPE_20 = 1;
    const NDS_TYPE_10 = 2;
    const NDS_TYPE_120 = 3;
    const NDS_TYPE_110 = 4;
    const NDS_TYPE_0 = 5;
    const NDS_TYPE_WO = 6;

    /**
     * создание объекта из ассоциативного массива
     * возвращает экземпляр объекта
     *
     * @param $data array
     * @return Item
     */
    public static function fromData($data)
    {
        $instance = new Item();
        return $instance->addData($data);
    }
}