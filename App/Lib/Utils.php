<?php

namespace App\Lib;

/**
 * 通用工具类
 */
class Utils {
	/**
     * 生成的唯一性key
     * @param string $str
     * @return string 
    */
    public static function getFileKey($str) {
        return substr(md5(self::makeRandomString() . $str . time() . rand(0, 9999)), 8, 16);
    }

    /**
     * 生成随机字符串
     * @param string $length 长度
     * @return string 生成的随机字符串
     */
    public static function makeRandomString($length = 1) { 
  		
        $str = '';
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for($i=0; $i<$length; $i++) {
            $str .= $strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        return $str;
  }
}