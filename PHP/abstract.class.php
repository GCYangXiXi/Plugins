<?php

/*
 * 接口中所有方法的访问限制都必须为public
 * 接口中可以声明常量,声明常量使用const关键字,但不可以声明属性
 */

interface Whatever {

    const whatever = 0;

    public function one();

    public function two();
}

interface Whatever_two {

    public function three();

    public function four();
}

/*
 * 抽象类中必须有至少一个抽象方法
 * 子类在继承抽象类时,必须实现抽象类中的所有抽象方法
 */

abstract class Test {

    abstract public function aa();

    protected function bb()
    {
        return 123;
    }

}

/*
 * 抽象类的单一继承与接口的多继承
 * 
 */

class Son extends Test implements Whatever, Whatever_two {

    function aa()
    {
        
    }

    protected static function one()
    {
        
    }

    final private function two()
    {
        
    }

    public function three()
    {
        
    }

    public function four()
    {
        
    }

}
