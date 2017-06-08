<?php

/*
 * 图形验证码插件
 * 
 * @param $_width int 验证码图片的宽度
 * @param $_height int 验证码图片的高度
 * @param $_length int 验证码的长度
 * @param $_fontSize int 验证码的字体大小
 * @param $code int 用户输入的验证码
 * 
 */
session_start();

class VerifyCode {

    private $_width;
    private $_height;
    private $_length;
    private $_fontSize;
    private $_fontFamily;
    private $_canvas; //用来保存画布资源
    private $_code = ''; //用来保存验证码字段

    /**
     * 初始化时自动创建画布资源及填充背景
     * @param int $width
     * @param int $height
     * @param int $length
     * @param int $fontSize
     */

    public function __construct($width, $height, $length = 4, $fontSize = 18, $fontFamily = 'SourceCodePro.ttf')
    {
        $this->_width = $width;
        $this->_height = $height;
        $this->_length = $length;
        $this->_fontSize = $fontSize;
        $this->_fontFamily = $fontFamily;
        $this->_canvas = imagecreatetruecolor($this->_width, $this->_height);
        $bgcolor = imagecolorallocate($this->_canvas, 245, 245, 245);
        imagefill($this->_canvas, 0, 0, $bgcolor);
    }

    /**
     * 创建干扰噪点
     */
    private function _writeNoise()
    {
        for ($i = 0; $i < $this->_length * 100; $i++)
        {
            $x = rand(0, $this->_width);
            $y = rand(0, $this->_height);
            $pixelcolor = imagecolorallocate($this->_canvas, rand(50, 200), rand(50, 200), rand(50, 200));
            imagesetpixel($this->_canvas, $x, $y, $pixelcolor);
        }
    }

    /**
     * 创建干扰线段
     */
    private function _writeLine()
    {
        for ($i = 0; $i < $this->_length; $i++)
        {
            $x1 = rand(0, $this->_width);
            $y1 = rand(0, $this->_height);
            $x2 = rand(0, $this->_width);
            $y2 = rand(0, $this->_height);
            $linecolor = imagecolorallocate($this->_canvas, rand(80, 220), rand(80, 220), rand(80, 220));
            imageline($this->_canvas, $x1, $y1, $x2, $y2, $linecolor);
        }
    }

    /**
     * 创建图形验证码,并将验证码的值存入session中
     */
    public function createVerifyCode()
    {
        $this->_writeNoise();
        $this->_writeLine();
        for ($i = 0; $i < $this->_length; $i++)
        {
            $angle = rand(-20, 20);
            $x = ($this->_width / $this->_length) * $i + 5;
            $y = rand($this->_height / 2, $this->_height * 2 / 3);
            $color = imagecolorallocate($this->_canvas, rand(0, 120), rand(0, 120), rand(0, 120));
            $code = 'abcdefghijklmnopqrstuvwxyz1234567890';
            $text = substr($code, rand(0, strlen($code) - 1), 1);
            imagettftext($this->_canvas, $this->_fontSize, $angle, $x, $y, $color, $this->_fontFamily, $text);
            $this->_code .= $text;
        }
        $_SESSION['code'] = $this->_code;
        //输出验证码图片
        header('content-type:image/png');
        imagepng($this->_canvas);
        //销毁
        imagedestroy($this->_canvas);
    }

    /**
     * 验证验证码是否正确
     * @param string $code
     * @return boolean
     */
    public function checkCode($code)
    {
        if (strtolower($code) == $_SESSION['code'])
        {
            return true;
        }
        return false;
    }

}

$varifyCode = new VerifyCode(120, 40);
$varifyCode->createVerifyCode();

