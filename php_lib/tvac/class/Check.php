<?php 
class Check
{	
	public static function isEmpty($s)
	{
		if( !$s ) return true;

		return false;
	}

	/**
	 *  時刻形式のチェックを行います
	 */
	public static function time($s)
	{
		if( $s == false ) return true;

		$s = str_replace(':', '', $s);
		if( strlen($s) != 4 ) return true;

		if(is_numeric($s) == false ) return true;

		return false;
	}

	/**
	 *  数値形式のチェックを行います
	 */
	public static function number(&$s)
	{
		$s = trim($s);

		if(!$s) return false;

		$s = mb_convert_kana($s,'n');
		return !$s || is_numeric($s) == false;
	}

	/**
	 *  Ym形式のチェックを行います。
	 */
	public static function Ym($s)
	{
		if( !$s ) return true;

		$s = explode('/',$s);

		if( !isset($s[0]) || !$s[0] || strlen($s[0]) != 4 || is_numeric($s[0]) == false ) return true;

		if( !isset($s[1]) || !$s[1] || strlen($s[1]) != 2 || is_numeric($s[1]) == false ) return true;

		return false;
	}

	/**
	 *  Ym形式のチェックを行います。
	 */
	public static function Ymd($s)
	{
		if( !$s ) return true;

		$s = explode('/',$s);

		if( !isset($s[0]) || !$s[0] || strlen($s[0]) != 4 || is_numeric($s[0]) == false ) return true;

		if( !isset($s[1]) || !$s[1] || strlen($s[1]) != 2 || is_numeric($s[1]) == false ) return true;

		if( !isset($s[2]) || !$s[2] || strlen($s[2]) != 2 || is_numeric($s[2]) == false ) return true;

		return false;
	}

    /*
     * 文字数取得
     */
	public static function StrLength($s)
	{
		if( !$s ) return 0;

        if(is_array($s)){
            return mb_strlen( implode('',$s));
        }else{
            return mb_strlen($s);
        }

	}

	public static function MaxLength($s,$len)
	{
		if( !$s ) return false;

		if(is_array($s))
		{
			return mb_strlen( implode('',$s)) > $len;
		}

		return mb_strlen($s) > $len;
	}

	public static function Mail($s)
	{
		if( !$s ) return false;
		return strpos($s,'@') == false;
	}

	public static function Kana($s)
	{
		$s = trim($s);
		
		if( !$s ) return false;

		mb_regex_encoding("utf8"); 
		$s = mb_convert_kana($s,'K');
		return mb_ereg("^[ア-ン゛゜ァ-ォャ-ョー「」、ヴ]+$", $s)== false;
	}

}