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
 *  VsevkassuAPI.php
 *  Основной файл SDK для вызова методов API
 */

namespace vsevkassu\sdk;

class VsevkassuAPI {

    /**
     * Адрес сервиса API
     * @var string
     */
    private $_host = 'https://vsevkassu.ru/api/v1';

    /**
     * Логин
     * @var string
     */
    public $login;

    /**
     * Пароль
     * @var string
     */
    public $password;

    /**
     * Токен
     * @var string
     */
    public $token;

    public function setHost($host)
    {
        $this->_host = $host;
    }


    /**
     * Функция авторизации, вызывается для установки
     * токена по логину и паролю
     * Можно установить токен сразу после создания объекта
     *
     *  $api = new VsevkassuAPI();
     *  $api->token = 'xxxxxxxxxxxxxxxxxxxxxxxxx';
     *
     * При этом не требуется логин и пароль
     * и функцию авторизации вызывать не нужно
     *
     * @return bool
     */
    public function auth()
    {
        $data = json_encode(array(
            'login' => $this->login,
            'password' => $this->password
        ));
        $result = $this->request('auth', false, 'POST', $data);
        $this->token = isset($result['token']) ? $result['token'] : '';
        return (!empty($this->token));
    }

    /**
     * Версия API
     * @return string
     */
    public function getVer()
    {
        $result = $this->request('');
        return $result['APIVersion'];
    }

    /**
     * Возвращает листинг смен с использованием фильтра
     * Фильтр - обязательный параметр,
     * если фильтр не используется - создаем пустой объект
     *
     * $Shifts = $api->findShifts(new ShiftFilter());
     *
     * Возвращает массив объектов vsevkassu\sdk\Shift
     *
     * @param ShiftFilter $filter
     * @return array
     */
    public function findShifts(ShiftFilter $filter)
    {
        $data = $this->request('shift', false, 'GET', (string) $filter);
        $result = [];
        foreach ($data as $datum) {
            $result[] = Shift::fromData($datum);
        }
        return $result;
    }

    /**
     * Возвращает смену vsevkassu\sdk\Shift по указанному
     * системному идентификатору
     * Если смена не найдена, возвращает false
     *
     * @param $id
     * @return Shift|bool
     */
    public function findShift($id)
    {
        $data = $this->request('shift', $id, 'GET');
        return ($data) ? Shift::fromData($data) : false;
    }

    /**
     * Создает документ на открытие смены
     * Передаваемые параметры $salepoint_id - системный идентификатор кассы
     * $cashier_id - системный идентификатор кассира, можно не указывать
     * если в системе для данной кассы установлен кассир по умолчанию
     * Возвращает номер документа
     *
     * @param int $salepoint_id
     * @param int $cashier_id
     * @return int
     */
    public function openShift($salepoint_id, $cashier_id = false)
    {
        $Shift = Shift::createShift(Shift::SHIFT_TYPE_OPEN, $salepoint_id, $cashier_id);
        $data = $this->request('shift', false, 'POST', json_encode($Shift));
        return $data['receipt_id'];
    }

    /**
     * Создает документ на закрытие смены
     * Передаваемые параметры $salepoint_id - системный идентификатор кассы
     * Возвращает номер документа
     *
     * @param $salepoint_id
     * @return int
     */
    public function closeShift($salepoint_id)
    {
        $Shift = Shift::createShift(Shift::SHIFT_TYPE_CLOSE, $salepoint_id);
        $data = $this->request('shift', false, 'POST', json_encode($Shift));
        return $data['receipt_id'];
    }

    /**
     * Возвращает листинг кассовых аппаратов с использованием фильтра
     * Фильтр - обязательный параметр,
     * если фильтр не используется - создаем пустой объект
     *
     * $Cashboxes = $api->findCashboxes(new CashboxFilter());
     *
     * Возвращает массив объектов vsevkassu\sdk\Cashbox
     *
     * @param CashboxFilter $filter
     * @return array
     */
    public function findCashboxes(CashboxFilter $filter)
    {
        $data = $this->request('cashbox', false, 'GET', (string) $filter);
        $result = [];
        foreach ($data as $datum) {
            $result[] = Cashbox::fromData($datum);
        }
        return $result;
    }

    /**
     * Возвращает кассу vsevkassu\sdk\Cashbox по указанному
     * системному идентификатору
     * Если касса не найдена, возвращает false
     *
     * @param $id
     * @return bool|Cashbox
     */
    public function findCashbox($id)
    {
        $data = $this->request('cashbox', $id, 'GET');
        return ($data) ? Cashbox::fromData($data) : false;
    }

    /**
     * Возвращает листинг кассиров с использованием фильтра
     * Фильтр - обязательный параметр,
     * если фильтр не используется - создаем пустой объект
     *
     * $Cashiers = $api->findCashiers(new CashierFilter());
     *
     * Возвращает массив объектов vsevkassu\sdk\Cashier
     *
     * @param CashierFilter $filter
     * @return array
     */
    public function findCashiers(CashierFilter $filter)
    {
        $data = $this->request('cashier', false, 'GET', (string) $filter);
        $result = [];
        foreach ($data as $datum) {
            $result[] = Cashier::fromData($datum);
        }
        return $result;
    }

    /**
     * Возвращает кассира vsevkassu\sdk\Cashier по указанному
     * системному идентификатору
     * Если кассир не найден, возвращает false
     *
     * @param $id
     * @return bool|Cashier
     */
    public function findCashier($id)
    {
        $data = $this->request('cashier', $id, 'GET');
        return ($data) ? Cashier::fromData($data) : false;
    }

    /**
     * Возвращает листинг кассовых чеков с использованием фильтра
     * Фильтр - обязательный параметр,
     * если фильтр не используется - создаем пустой объект
     *
     * $Receipts = $api->findReceipts(new ReceiptFilter());
     *
     * Возвращает массив объектов vsevkassu\sdk\Receipt
     *
     * @param ReceiptFilter $filter
     * @return array
     */
    public function findReceipts(ReceiptFilter $filter)
    {
        $data = $this->request('receipt', false, 'GET', (string) $filter);
        $result = [];
        foreach ($data as $datum) {
            $result[] = Receipt::fromData($datum);
        }
        return $result;
    }

    /**
     * Возвращает чек vsevkassu\sdk\Receipt по указанному
     * системному идентификатору
     * Если чек не найден, возвращает false
     *
     * @param $id
     * @return bool|Receipt
     */
    public function findReceipt($id)
    {
        $data = $this->request('receipt', $id, 'GET');
        return ($data) ? Receipt::fromData($data) : false;
    }

    /**
     * Создает документ кассового чека
     * Для создания чека нужно подготовить объект vsevkassu\sdk\Receipt
     * и заполнить необходимые поля, после чего объект передается
     * данной функции.
     * При успешном сохранении чека вернется объект vsevkassu\sdk\Receipt
     * с заполненным полем id и другими расчетными параметрами
     * при неуспешном исходе вызывается Exception
     *
     * @param Receipt $receipt
     * @return bool|Receipt
     */
    public function saveReceipt(Receipt $receipt)
    {
        $data = $this->request('receipt', false, 'PUT', json_encode($receipt));
        return ($data) ? Receipt::fromData($data) : false;
    }

    /**
     * Функция запроса к API
     *
     * @param $command
     * @param bool $id
     * @param string $method
     * @param string $data
     * @return bool|mixed|string
     * @throws \Exception
     */
    public function request($command, $id = false, $method = 'GET', $data = '')
    {
        $url = $this->_host . '/' . $command;

        if($id)
            $url .= '/' . $id;

        if(in_array($method, array('GET')) && !empty($data))
            $url .= '?' . $data;

        $headers = [
            'Content-Type: application/json'
        ];

        if(!in_array($command, ['index', 'auth'])){
            $headers[] = 'X-Api-Key: ' . $this->token;
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if (in_array($method, array('POST', 'PUT'))) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        $response = curl_exec($ch);
        $request_data = curl_getinfo($ch);
        $headers_str = substr($response, 0, $request_data['header_size']);
        $body = substr($response, $request_data['header_size']);
        curl_close($ch);

        $body = json_decode($body, true);

        if($request_data['http_code'] !== 200)
            throw new \Exception($body['message'], $body['code']);

        return $body;
    }

}