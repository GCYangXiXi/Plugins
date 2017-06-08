<?php

/**
 * 操作mysql数据库类,具有连接,增删改查,显示sql语句等功能
 */
class DB {

    private $_conn; //保存连接成功后返回的mysql连接标识符
    private $_sql; //保存sql语句
    private static $_instance = null; //保存实例

    /**
     * 初始化连接数据库
     * @param string $host 数据库地址
     * @param string $username  数据库用户名
     * @param string $password  数据库密码
     * @param string $dbname  数据库名称
     */

    private function __construct($host, $username, $password, $dbname)
    {
        $this->_conn = @mysql_connect($host, $username, $password) or die('数据库连接失败：' . mysql_error());
        mysql_select_db($dbname, $this->_conn) or die('数据库连接失败：' . mysql_error());
        mysql_query('set names utf8', $this->_conn);
    }

    /**
     * 禁止外部克隆
     */
    private function __clone()
    {
        
    }

    /**
     * 获取数据库实例
     * @param string $host 数据库地址
     * @param string $username  数据库用户名
     * @param string $password  数据库密码
     * @param string $dbname  数据库名称
     * @return object 该数据库实例
     */
    public static function getInstance($host, $username, $password, $dbname)
    {
        is_null(self::$_instance) && self::$_instance = new self($host, $username, $password, $dbname);
        return self::$_instance;
    }

    /**
     * 查询数据库
     * @param string $table 数据表名称
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @return array 查询结果
     */
    public function select($table, $condition = array(), $field = array())
    {
        $where = '';
        if (!empty($condition))
        {
            foreach ($condition as $k => $v)
            {
                $where .= $k . "='" . $v . "' and ";
            }
            $where = 'where ' . $where . '1=1';
        }
        $fieldstr = '';
        if (!empty($field))
        {
            foreach ($field as $k => $v)
            {
                $fieldstr .= $v . ',';
            }
            $fieldstr = rtrim($fieldstr, ',');
        }
        else
        {
            $fieldstr = '*';
        }
        $this->_sql = "select {$fieldstr} from {$table} {$where}";
        $result = mysql_query($this->_sql, $this->_conn);
        $resuleRow = array();
        $i = 0;
        while ($row = mysql_fetch_assoc($result))
        {
            foreach ($row as $k => $v)
            {
                $resuleRow[$i][$k] = $v;
            }
            $i++;
        }
        return $resuleRow;
    }

    /**
     * 插入记录
     * @param string  $table    数据表名称
     * @param array $data   要插入的数据
     * @return int/boolean  成功时返回影响记录的id,失败时返回false
     */
    public function insert($table, $data)
    {
        $values = '';
        $datas = '';
        foreach ($data as $k => $v)
        {
            $values .= $k . ',';
            $datas .= "'$v'" . ',';
        }
        $values = rtrim($values, ',');
        $datas = rtrim($datas, ',');
        $this->_sql = "INSERT INTO  {$table} ({$values}) VALUES ({$datas})";
//        return $this->_sql;
        if (mysql_query($this->_sql))
        {
            return mysql_insert_id();
        }
        return false;
    }

    /**
     * 修改记录
     * @param string $table     数据表名称
     * @param array $data   要更新的数据
     * @param array $condition      条件
     * @return boolean  成功时返回true,失败时返回false
     */
    public function update($table, $data, $condition = array())
    {
        $where = '';
        if (!empty($condition))
        {

            foreach ($condition as $k => $v)
            {
                $where .= $k . "='" . $v . "' and ";
            }
            $where = 'where ' . $where . '1=1';
        }
        $updatastr = '';
        if (!empty($data))
        {
            foreach ($data as $k => $v)
            {
                $updatastr .= $k . "='" . $v . "',";
            }
            $updatastr = 'set ' . rtrim($updatastr, ',');
        }
        $this->_sql = "update {$table} {$updatastr} {$where}";
        return mysql_query($this->_sql);
    }

    /**
     * 删除记录
     * @param string $table     数据表名称
     * @param array $condition      条件
     * @return boolean  成功时返回true,失败时返回false
     */
    public function delete($table, $condition)
    {
        $where = '';
        if (!empty($condition))
        {

            foreach ($condition as $k => $v)
            {
                $where .= $k . "='" . $v . "' and ";
            }
            $where = 'where ' . $where . '1=1';
        }
        $this->_sql = "delete from {$table} {$where}";
        return mysql_query($this->_sql);
    }

    /**
     * 获取上一条sql语句
     * @return string 
     */
    public function getLastSql()
    {
        return $this->_sql;
    }

}

$db = DB::getInstance('127.0.0.1', 'root', '', 'ahhedi');
//$list = $db->select('demo',array('name'=>'tom','password'=>'ds'),array('name','password'));  
//echo $db->insert('demo',array('name'=>'最近你啦','password'=>'123'));  
//echo $db->update('demo',array("name"=>'xxx',"password"=>'123'),array('id'=>1));  
//echo $db->delete('demo', array('id' => '2'));
//db::getLastSql();
//echo "<pre>";
//var_dump($db->select('admin'));
$db->insert('banner', array('type' => 6, 'name' => 'banner', 'url' => '/', 'pic' => '/', 'add_time' => time(), 'visible' => 1));
echo $db->getLastSql();
