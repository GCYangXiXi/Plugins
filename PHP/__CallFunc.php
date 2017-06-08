<?php

class DB {

    private $sql = array(
        "field" => "",
        "where" => "",
        "order" => "",
        "limit" => "",
        "group" => "",
        "having" => ""
    );

    private function __call($functionName, $arr)
    {     //只有在私有成员数组中存在的键才能被调用
        $functionName = strtolower($functionName);
        if (array_key_exists($functionName, $this->sql))
        {
            $this->sql[$functionName] = $arr[0];
        }
        else
        {
            echo "调用的方法不存在";
        }
        return $this;
    }

    public function select()
    {
        echo "select from {$this->sql['field']} user {$this->sql['where']} {$this->sql['order']} {$this->sql['limit']} {$this->sql['group']} {$this->sql['having']}";
    }

}

$db = new DB();
$db->field('sex count(sex)')               //只有在私有成员数组中存在的键才能被调用
        ->where('where sex in("m","w")')
        ->group('group by sex')
        ->having('having avg(age) > 25')
        ->select();
$db->query('d');                           //这个方法不存在就不能调用
