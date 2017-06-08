<?php

/**
 * 测试单例模式
 */
final class SingletonMode {

    private static $_instance = null; //私有静态属性，用于储存该类的对象

    private function __construct()
    {
        //禁止被实例化
    }

    private function __clone()
    {
        //禁止被克隆
    }

    public static function getInstance()
    {
        is_null(self::$_instance) && self::$_instance = new self;
        return self::$_instance;
    }

}

$objA = SingletonMode::getInstance();
$objB = SingletonMode::getInstance();
var_dump($objA === $objB);
