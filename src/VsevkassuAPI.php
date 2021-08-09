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

class VsevkassuAPI
{

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
        $data = $this->request('shift', false, 'GET', (string)$filter);
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
        $data = $this->request('cashbox', false, 'GET', (string)$filter);
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
        $data = $this->request('cashier', false, 'GET', (string)$filter);
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
        $data = $this->request('receipt', false, 'GET', (string)$filter);
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

    /*******************************************************************************************************************
     *
     * Функции работы с заказами
     * Позволяет формировать правильные чеки в зависимости от контекста
     * Подробнее см. документацию https://vsevkassu.ru/docs/api/store
     *
     ******************************************************************************************************************/

    /**
     * Возвращает листинг заказов с использованием фильтра
     * Фильтр - обязательный параметр,
     * если фильтр не используется - создаем пустой объект
     *
     * $Orders = $api->findOrders(new OrderFilter());
     *
     * Возвращает массив объектов vsevkassu\sdk\Order
     *
     * @param OrderFilter $filter
     * @return array of Order
     */
    public function findOrders(OrderFilter $filter)
    {
        $data = $this->request('store/order', false, 'GET', (string)$filter);
        $result = [];
        foreach ($data as $datum) {
            $result[] = Order::fromData($datum);
        }
        return $result;
    }

    /**
     * Возвращает заказ vsevkassu\sdk\Order по указанному
     * системному идентификатору
     * Если заказ не найден, возвращает false
     *
     * @param $id
     * @return bool|Order
     */
    public function findOrder($id)
    {
        $data = $this->request('store/order', $id, 'GET');
        return ($data) ? Order::fromData($data) : false;
    }

    /**
     * Добавляет новый заказ или изменяет аттрибуты существующего заказа
     * Игнорирует изменения товарных позиций в заказе,
     * для управления товарными позициями служат методы addOrderProduct, changeOrderProduct, deleteOrderProduct
     *
     * @param Order $order
     * @return bool|Order
     */
    public function saveOrder(Order $order)
    {
        $method = ($order->id) ? 'POST' : 'PUT';
        $data = $this->request('store/order', $order->id, $method, json_encode($order));
        return ($data) ? Order::fromData($data) : false;
    }

    /**
     * Удаляет существующий заказ по его идентификатору.
     * Удаление возможно только для заказов в статусе «Создан».
     * При прохождении любой оплаты удаление заказа невозможно.
     *
     * @param $id
     * @return bool
     */
    public function deleteOrder($id)
    {
        $this->request('store/order', $id, 'DELETE');
        return true;
    }

    /**
     * Возвращает листинг продуктов с использованием фильтра
     * Фильтр - обязательный параметр,
     * если фильтр не используется - создаем пустой объект
     *
     * $Products = $api->findProducts(new ProductFilter());
     *
     * Возвращает массив объектов vsevkassu\sdk\Product
     *
     * @param ProductFilter $filter
     * @return array of Product
     */
    public function findProducts(ProductFilter $filter)
    {
        $data = $this->request('store/product', false, 'GET', (string)$filter);
        $result = [];
        foreach ($data as $datum) {
            $result[] = Product::fromData($datum);
        }
        return $result;
    }

    /**
     * Возвращает продукт vsevkassu\sdk\Product по указанному
     * системному идентификатору
     * Если продукт не найден, возвращает false
     *
     * @param $id
     * @return bool|Product
     */
    public function findProduct($id)
    {
        $data = $this->request('store/product', $id, 'GET');
        return ($data) ? Product::fromData($data) : false;
    }

    /**
     * Добавляет новый продукт или изменяет аттрибуты существующего продукта
     *
     * @param Product $product
     * @return bool|Product
     */
    public function saveProduct(Product $product)
    {
        $method = ($product->id) ? 'POST' : 'PUT';
        $data = $this->request('store/product', $product->id, $method, json_encode($product));
        return ($data) ? Product::fromData($data) : false;
    }

    /**
     * Архивация продукта
     * Архивация - аналог процедуры удаления продукта.
     * Продукт удалить невозможно, т.к. он может быть связан с заказами и чеками.
     * Архивация скроет продукт из списков продуктов, если он уже не используется.
     * Продукт можно восстановить из архива, см. документацию
     * https://vsevkassu.ru/docs/api/store-products#%D0%B0%D1%80%D1%85%D0%B8%D0%B2%D0%B0%D1%86%D0%B8%D1%8F-%D0%BF%D1%80%D0%BE%D0%B4%D1%83%D0%BA%D1%82%D0%B0
     *
     * @param $id
     * @return bool
     */
    public function deleteProduct($id)
    {
        $this->request('store/product', $id, 'DELETE');
        return true;
    }

    /**
     * Добавляет продукт, указанный в объекте позиции заказа \vsevkassu\sdk\OrderProduct
     * к существующему заказу, в объекте \vsevkassu\sdk\Order
     * На момент добавления позиций заказа, заказ должен быть сохранен
     * Возвращает полный объект заказа с изменениями \vsevkassu\sdk\Order
     *
     * @param Order $order
     * @param OrderProduct $order_product
     * @return bool|Order
     * @throws \Exception
     */
    public function addOrderProduct(Order $order, OrderProduct $order_product)
    {
        $this->_checkOrderSaved($order);
        $command = "store/order/{$order->id}/product";
        $data = $this->request($command, false, 'PUT', json_encode($order_product));
        return ($data) ? Order::fromData($data) : false;
    }

    /**
     * Изменяет параметры позиции заказа, переданные в объекте \vsevkassu\sdk\OrderProduct
     * для заказа \vsevkassu\sdk\Order
     * объекты позиции заказа хранятся в заказе, в поле products
     * для изменения находим нужный объект позиции, изменяем его и передаем в аргумент данной функции.
     * Возвращает полный объект заказа с изменениями \vsevkassu\sdk\Order
     *
     * @param Order $order
     * @param OrderProduct $orderProduct
     * @return bool|Order
     * @throws \Exception
     */
    public function changeOrderProduct(Order $order, OrderProduct $orderProduct)
    {
        $this->_checkOrderSaved($order);

        if(!$orderProduct->id)
            throw new \Exception('id позиции в заказе отсутствует');

        $command = "store/order/{$order->id}/product";
        $data = $this->request($command, $orderProduct->id, 'POST', json_encode($orderProduct));
        return ($data) ? Order::fromData($data) : false;

    }

    /**
     * Производит удаление позиции заказа, которая передается в качестве аргумента в объекте \vsevkassu\sdk\OrderProduct
     * для заказа \vsevkassu\sdk\Order
     * объекты позиции заказа хранятся в заказе, в поле products
     * для удаления находим нужный объект позиции, изменяем его и передаем в аргумент данной функции.
     * Возвращает полный объект заказа с изменениями \vsevkassu\sdk\Order
     *
     * @param Order $order
     * @param OrderProduct $orderProduct
     * @return bool|Order
     * @throws \Exception
     */
    public function deleteOrderProduct(Order $order, OrderProduct $orderProduct)
    {
        $this->_checkOrderSaved($order);

        if(!$orderProduct->id)
            throw new \Exception('id позиции в заказе отсутствует');

        $command = "store/order/{$order->id}/product";
        $data = $this->request($command, $orderProduct->id, 'DELETE');
        return ($data) ? Order::fromData($data) : false;
    }

    /**
     * Добавляет оплату к заказу
     * На момент добавления оплаты, заказ должен быть сохранен.
     * В качестве второго аргумента принимается объект оплаты \vsevkassu\sdk\OrderPayment
     * с заполненныим параметрами: sum_nal, sum_bn, shipped, accepted
     * все параметры необязательны, но обязательно условие sum_nal + sum_bn > 0
     * Если передан параметр accepted, то оплата сразу проводится.
     * Возвращается объект заказа со всеми изменениями
     *
     * @param Order $order
     * @param OrderPayment $payment
     * @return bool|Order
     */
    public function addOrderPayment(Order $order, OrderPayment $payment)
    {
        $this->_checkOrderSaved($order);
        $command = "store/order/{$order->id}/payment";
        $data = $this->request($command, false, 'PUT', json_encode($payment));
        return ($data) ? Order::fromData($data) : false;
    }

    /**
     * Изменяет оплату по заказу
     * Если оплата проведена, то ее изменить нельзя, будет ошибка
     * для изменения оплаты берем необходимый объект из коллекции заказа payments
     * изменяем и передаем в качестве второго аргумента
     * Возвращается объект заказа со всеми изменениями
     *
     * @param Order $order
     * @param OrderPayment $payment
     * @return bool|Order
     * @throws \Exception
     */
    public function changeOrderPayment(Order $order, OrderPayment $payment)
    {
        $this->_checkOrderSaved($order);

        if(!$payment->id)
            throw new \Exception('id оплаты отсутствует');

        $command = "store/order/{$order->id}/payment";
        $data = $this->request($command, $payment->id, 'POST', json_encode($payment));
        return ($data) ? Order::fromData($data) : false;
    }

    /**
     * Удаляет оплату по заказу
     * Если оплата проведена, то ее удалить нельзя, будет ошибка
     * для удаления оплаты берем необходимый объект из коллекции заказа payments
     * и передаем в качестве второго аргумента
     * Возвращается объект заказа со всеми изменениями
     *
     * @param Order $order
     * @param OrderPayment $payment
     * @return bool|Order
     * @throws \Exception
     */
    public function deleteOrderPayment(Order $order, OrderPayment $payment)
    {
        $this->_checkOrderSaved($order);

        if(!$payment->id)
            throw new \Exception('id оплаты отсутствует');

        $command = "store/order/{$order->id}/payment";
        $data = $this->request($command, $payment->id, 'DELETE');
        return ($data) ? Order::fromData($data) : false;
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

    private function _checkOrderSaved(Order $order)
    {
        if(!$order->id)
            throw new \Exception('Заказ не сохранен. Операция возможна только при наличии id');
    }

}