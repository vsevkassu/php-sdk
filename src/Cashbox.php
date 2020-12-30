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
 *  Cashbox.php
 *  Объект представляющий кассовый аппарат (точку продаж)
 */

namespace vsevkassu\sdk;

class Cashbox extends BaseObject {

    /**
     * Данные при запросе листинга ККТ
     */

    /**
     * системный идентификатор кассы
     * @var int
     */
    public $id;

    /**
     * адрес установки кассового аппарата
     * @var string
     */
    public $address;

    /**
     * место установки кассовго аппарата
     * @var string
     */
    public $place;

    /**
     * серийный номер ккт
     * @var string
     */
    public $kkt_serial;

    /**
     * регистрационный номер ккт
     * @var string
     */
    public $kkt_reg_num;

    /**
     * модель ккт
     * @var string
     */
    public $model;

    /**
     * наименование организации
     * @var string
     */
    public $organization_name;

    /**
     * ИНН оргнизации
     * @var string
     */
    public $organization_inn;

    /**
     * Данные при запросе детальной информации о кассовом аппарате
     * Также включены данные из листинга
     */

    /**
     * систмный номер организации
     * @var int
     */
    public $org_id;

    /**
     * Дата последнего соединения ККТ с сервисом, формат YYYY-MM-DD HH:MM:SS
     * @var string
     */
    public $last_connect;

    /**
     * Наименование ОФД
     * @var string
     */
    public $ofd_name;

    /**
     * ИНН ОФД
     * @var string
     */
    public $ofd_inn;

    /**
     * Хост ОФД
     * @var string
     */
    public $ofd_host;

    /**
     * Порт ОФД
     * @var int
     */
    public $ofd_port;

    /**
     * DNS ОФД
     * @var string
     */
    public $ofd_dns;

    /**
     * Серийный номер автомата
     * @var string
     */
    public $automat_serial;

    /**
     * Признак установки в автомате
     * @var bool
     */
    public $automat_sign;

    /**
     * Признак автономной кассы
     * @var bool
     */
    public $standalone_sign;

    /**
     * Признак шифрования
     * @var bool
     */
    public $encrypt_sign;

    /**
     * Призак агента
     * @var int
     */
    public $agent_byte;

    /**
     * Признак расчетов только в сети Интернет
     * @var bool
     */
    public $internetonly_sign;

    /**
     * Признак только услуги
     * @var bool
     */
    public $service_sign;

    /**
     * Признак БСО
     * @var bool
     */
    public $bso_sign;

    /**
     * Признак азартных игр
     * @var bool
     */
    public $game_sign;

    /**
     * признак торговли акцизными товарами
     * @var bool
     */
    public $excize_sign;

    /**
     * Признак установки принтера в автомате
     * @var bool
     */
    public $printer_sign;

    /**
     * Сайт ФНС
     * @var string
     */
    public $fns_site;

    /**
     * Сайт проверки чеков
     * @var string
     */
    public $check_site;

    /**
     * E-Mail отправителя
     * @var string
     */
    public $sender_email;

    /**
     * Текущий номер смены
     * @var int
     */
    public $shift_number;

    /**
     * Текущее состояние смены
     * @var string
     */
    public $shift_state;

    /**
     * Системный номер текущего кассира
     * @var int
     */
    public $cashier_id;

    /**
     * системный номер кассира по умолчанию
     * @var int
     */
    public $default_cashier_id;

    /**
     * признак блокировки
     * @var bool
     */
    public $blocked;

    /**
     * Признак необходимости регистрации
     * @var bool
     */
    public $need_register;

    /**
     * признак необходимости перерегистрации после изменения данных
     * @var bool
     */
    public $need_reregister;

    /**
     * Система налогообложения по умолчанию
     * @var int
     */
    public $default_tax_type;

    /**
     * признак проведения лотереи
     * @var bool
     */
    public $lottery_sign;

    /**
     * Причина перерегистрации
     * @var int
     */
    public $reregister_reason;

    /**
     * Количество документов непереданных в ОФД
     * @var int
     */
    public $ofd_notsent;

    /**
     * Дата, с которой есть непереданные документы в ОФД
     * @var string
     */
    public $ofd_notsent_from;

    /**
     * Текст ошибки связи с ОФД
     * @var string
     */
    public $ofd_error;

    /**
     * Признак отправки чеков на почту
     * @var bool
     */
    public $send_receipts;

    /**
     * Система налогообложения организации
     * @var int
     */
    public $organization_tax;

    /**
     * создание объекта из ассоциативного массива
     * возвращает экземпляр объекта
     *
     * @param $data array
     * @return Cashbox
     */
    public static function fromData($data)
    {
        $instance = new Cashbox();
        return $instance->addData($data);
    }


}