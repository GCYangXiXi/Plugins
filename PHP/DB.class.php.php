<?php

/**
 * 操作mysql数据库类,具有连接,基本增删改查,显示sql语句等功能
 */
class DB {

    private $_conn; //保存连接成功后返回的mysql连接标识符
    private $_sql; //保存sql语句
    private $_tablePre; //表前缀
    private $_tableName; //数据表名称
    private $_coding; //字符集编码
    private static $_instance = null; //保存实例

    /**
     * 初始化连接数据库
     * @param string $host 数据库地址
     * @param string $username  数据库用户名
     * @param string $password  数据库密码
     * @param string $dbname  数据库名称
     */

    private function __construct($host, $username, $password, $dbname, $tablePre, $coding)
    {
        $this->_tablePre = $tablePre;
        $this->_coding = $coding;
        $this->_conn = @mysql_connect($host, $username, $password) or die('数据库连接失败：' . mysql_error());
        mysql_select_db($dbname, $this->_conn) or die('数据库选择失败：' . mysql_error());
        mysql_query('set names ' . $this->_coding, $this->_conn);
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
     * @return object 返回该数据库实例
     */
    public static function getInstance($host, $username, $password, $dbname, $tablePre = '', $coding = 'utf8')
    {
        is_null(self::$_instance) && self::$_instance = new self($host, $username, $password, $dbname, $tablePre = '', $coding = 'utf8');
        return self::$_instance;
    }

    /**
     * 查询数据库
     * @param string $table 数据表名称
     * @param array $condition 查询条件
     * @param array $field 查询字段
     * @return array 返回查询结果
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
        $this->getTableName($table);
        $this->_sql = "select {$fieldstr} from {$this->_tableName} {$where}";
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
     * @return int  成功时返回影响记录的id,失败时返回0
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
        $this->getTableName($table);
        $this->_sql = "INSERT INTO  {$this->_tableName} ({$values}) VALUES ({$datas})";
        mysql_query($this->_sql, $this->_conn);
        return mysql_insert_id();
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
        $this->getTableName($table);
        $this->_sql = "update {$this->_tableName} {$updatastr} {$where}";
        mysql_query($this->_sql, $this->_conn);
        return mysql_affected_rows();
    }

    /**
     * 删除记录
     * @param string $table     数据表名称
     * @param array $condition      条件
     * @return int 返回影响行数
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
        $this->getTableName($table);
        $this->_sql = "delete from {$this->_tableName} {$where}";
        mysql_query($this->_sql, $this->_conn);
        return mysql_affected_rows();
    }

    /**
     * 获取上一条sql语句
     * @return string 
     */
    public function getLastSql()
    {
        return $this->_sql;
    }

    /**
     * 获取完整数据表名称
     * @param string $tableName 
     */
    private function getTableName($tableName)
    {
        $this->_tableName = $tableName . $this->_tablePre;
    }

}

$db = DB::getInstance('127.0.0.1', 'root', '', 'ahhedi');
if ($db->delete('message', array('id' => 1)))
{
    echo '成功';
}
else
{
    echo '失败';
}
echo $db->getLastSql();
