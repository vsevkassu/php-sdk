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
 *  BaseFilter.php
 *
 */

namespace vsevkassu\sdk;

abstract class BaseFilter extends BaseObject {

    /**
     * Преобразование фильтра в строку запроса
     *
     * @return string
     */
    public function __toString()
    {
        $vars = get_object_vars($this);
        $pairs = [];
        foreach ($vars as $k => $v) {
            if(!empty($v))
                $pairs[] = $k . '=' . urldecode($v);
        }
        return implode('&', $pairs);
    }

}