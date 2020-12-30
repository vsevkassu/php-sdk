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
 *  Receipt.php
 *  Объект представляющий кассовый чек
 */


namespace vsevkassu\sdk;

class Receipt extends BaseObject {

    /**
     * Системный номер чека
     * @var int
     */
    public $id;

    /**
     * Системный номер кассы
     * @var int
     */
    public $salepoint_id;

    /**
     * Уникальный номер чека в системе пользователя
     * @var string
     */
    public $external_id;

    /**
     * Дата создания чека
     * @var string
     */
    public $created_at;

    /**
     * статус чека, константы STATUS_
     * @var string
     */
    public $status;

    /**
     * описание ошибки фискализации
     * @var string
     */
    public $error;

    /**
     * Признак вывода чека на печать на устройстве
     * @var bool
     */
    public $is_print;

    /**
     * Тип чека. Константы TYPE_
     * @var int
     */
    public $type;

    /**
     * Расшифровка типа чека.
     * @var string
     */
    public $type_name;

    /**
     * Системный номер организации
     * @var int
     */
    public $organization_id;

    /**
     * Наименование организации
     * @var string
     */
    public $organization_name;

    /**
     * ИНН Организации
     * @var string
     */
    public $organization_inn;

    /**
     * Имя кассира
     * @var string
     */
    public $cashier_name;

    /**
     * ИНН Кассира
     * @var string
     */
    public $cashier_inn;

    /**
     * Место установки кассовго аппарата
     * @var string
     */
    public $salepoint_place;

    /**
     * Адрес установки кассового аппарата
     * @var string
     */
    public $salepoint_address;

    /**
     * Серийный номер кассового аппарата
     * @var string
     */
    public $kkt_serial;

    /**
     * Регистрационный номер кассового аппарата
     * @var string
     */
    public $kkt_regnum;

    /**
     * Серийный номер фискального накопителя в кассовом аппарате
     * @var string
     */
    public $kkt_fs;

    /**
     * E-Mail, с которого отправляются кассовые чеки
     * @var string
     */
    public $kkt_sender_email;

    /**
     * Сайт ФНС
     * @var string
     */
    public $fns_site;

    /**
     * Дата фискализации чека
     * @var string
     */
    public $fiscal_date;

    /**
     * Номер смены
     * @var int
     */
    public $shift;

    /**
     * Номер чека в смене
     * @var int
     */
    public $doc_number;

    /**
     * Номер фискального действия с момента регистрации накопителя
     * @var int
     */
    public $fd_number;

    /**
     * Фискальный признак
     * @var string
     */
    public $sign;

    /**
     * Покупатель
     * @var Buyer
     */
    public $buyer;

    /**
     * Массив объектов Item
     * @var array
     */
    public $items = [];

    /**
     * Данные платежа
     * @var Payment
     */
    public $payment;

    /**
     * Наименование системы налогообложения
     * @var string
     */
    public $tax_name;

    /**
     * Система налогообложения, константы TAX_
     * @var int
     */
    public $tax;

    /**
     * Описание статусов чека
     * STATUS_WAIT - ожидает отправки на кассовый аппарат
     * STATUS_SEND - отправлен на кассовый аппарат
     * STATUS_OK - фискализация завершена успешно
     * STATUS_ERROR - ошибка фискализации, текст ошибки в поле error
     */
    const STATUS_WAIT = 'wait';
    const STATUS_SEND = 'send';
    const STATUS_OK = 'ok';
    const STATUS_ERROR = 'error';

    /**
     * Значения поля type - тип чека
     *
     * обычные чеки
     * TYPE_INCOME - Приход
     * TYPE_INCOME_RETURN - Возврат прихода
     * TYPE_OUTCOME - Расход
     * TYPE_OUTCOME_RETURN - Возврат расхода
     *
     * Чеки коррекции
     * TYPE_CORRECTION_INCOME - Коррекция прихода
     * TYPE_CORRECTION_INCOME_RETURN - Коррекция возврата прихода
     * TYPE_CORRECTION_OUTCOME - Коррекция расхода
     * TYPE_CORRECTION_OUTCOME_RETURN - Коррекция возврата расхода
     *
     * Бланки строгой отчетности (касса должна иметь флаг БСО)
     * TYPE_BSO_INCOME - БСО Приход
     * TYPE_BSO_INCOME_RETURN - БСО Возврат прихода
     * TYPE_BSO_OUTCOME - БСО Расход
     * TYPE_BSO_OUTCOME_RETURN - БСО Возврат расхода
     *
     * Чеки коррекции БСО
     * TYPE_BSO_CORRECTION_INCOME - БСО Коррекция прихода
     * TYPE_BSO_CORRECTION_INCOME_RETURN - БСО коррекция возврата прихода
     * TYPE_BSO_CORRECTION_OUTCOME - БСО коррекция расхода
     * TYPE_BSO_CORRECTION_OUTCOME_RETURN - БСО коррекция возврата расхода
     */
    const TYPE_INCOME = 1;
    const TYPE_INCOME_RETURN = 2;
    const TYPE_OUTCOME = 3;
    const TYPE_OUTCOME_RETURN = 4;
    const TYPE_CORRECTION_INCOME = 11;
    const TYPE_CORRECTION_INCOME_RETURN = 12;
    const TYPE_CORRECTION_OUTCOME = 13;
    const TYPE_CORRECTION_OUTCOME_RETURN = 14;
    const TYPE_BSO_INCOME = 21;
    const TYPE_BSO_INCOME_RETURN = 22;
    const TYPE_BSO_OUTCOME = 23;
    const TYPE_BSO_OUTCOME_RETURN = 24;
    const TYPE_BSO_CORRECTION_INCOME = 31;
    const TYPE_BSO_CORRECTION_INCOME_RETURN = 32;
    const TYPE_BSO_CORRECTION_OUTCOME = 33;
    const TYPE_BSO_CORRECTION_OUTCOME_RETURN = 34;

    /**
     * Значения поля tax - система налогообложения
     * TAX_OSN - общая система налогообложения
     * TAX_USN_INCOME - упрощенная система налогообложения доходы
     * TAX_USN_IO - упрощенная система налогообложения доходы минус расходы
     * TAX_ENVD - единый налог на вмененный доход
     * TAX_ESN - единый социальный налог
     * TAX_PATENT - патентная система налогообложения
     */
    const TAX_OSN = 0;
    const TAX_USN_INCOME = 1;
    const TAX_USN_IO = 2;
    const TAX_ENVD = 3;
    const TAX_ESN = 4;
    const TAX_PATENT = 5;

    public function addItem(Item $item)
    {
        $this->items[] = $item;
    }

    public function setBuyer(Buyer $buyer)
    {
        $this->buyer = $buyer;
    }

    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * создание объекта из ассоциативного массива
     * возвращает экземпляр объекта
     *
     * @param $data
     * @return Receipt
     */
    public static function fromData($data)
    {
        $instance = new Receipt();

        if(is_string($data))
            $data = json_decode($data, true);

        if($data) {
            foreach ($data as $name => $value) {
                switch ($name) {
                    case 'buyer' : $instance->buyer = Buyer::fromData($value);
                        break;
                    case 'payment' : $instance->payment = Payment::fromData($value);
                        break;
                    case 'items' :
                        foreach ($value as $item){
                            $instance->items[] = Item::fromData($item);
                        }
                        break;
                    default :
                        $instance->$name = $value;
                }
            }
        }

        return $instance;
    }
}