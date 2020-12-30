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
 *  BaseObject.php
 *
 */

namespace vsevkassu\sdk;

abstract class BaseObject implements \JsonSerializable {


    protected function addData($json){

        if(is_string($json))
            $data = json_decode($json, true);
        else
            $data = $json;
        if($data) {
            foreach ($data as $name => $value) {
                $this->$name = $value;
            }
        }

        return $this;
    }

    protected function toDate($value)
    {
        if(empty($value))
            return $value;

        if(!is_object($value)){
            if(is_string($value)){
                $value = new \DateTime($value);
            } else
                throw new \Exception('Неожиданный тип ');
        }elseif (get_class($value) != 'DateTime')
            throw new \Exception('Неожиданный тип ');

        return $value;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }

}