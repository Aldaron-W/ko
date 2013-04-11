<?php
/**
 * Escape
 *
 * @package ko
 * @subpackage view
 * @author zhangchu
 */

interface IKo_View_Escape
{
	/**
	 * ��װ addslashes �� _SForbidScript ����
	 *
	 * @return string
	 */
	public static function SAddSlashes($sInput);
	/**
	 * ��װ addslashes �� _SForbidScript �� htmlspecialchars ����
	 *
	 * @return string
	 */
	public static function SAddSlashesHtml($sInput);
	/**
	 * ��װ nl2br �� htmlspecialchars ����
	 *
	 * @return string
	 */
	public static function SMultiline($sInput);
	/**
	 * JSON_ENCODE
	 *
	 * @return string
	 */
	public static function SEscapeJson($vValue);
	/**
	 * ��HTML��Ϊ��ͨ�ı����õ��༭���У��� html�ı� �༭���༭
	 *
	 * @return string
	 */
	public static function SEscapeEditor($sValue, $sTextType='html');
	
	/**
	 * �����ı� input�༭/��ʾ �� �����ı� textarea�༭
	 */
	public static function VEscapeHtml($vValue, $aExclude=array());
	/**
	 * �����ı� �򵥵���ΪJS����
	 */
	public static function VEscapeSlashes($vValue, $aExclude=array());
	/**
	 * �����ı� ��ΪJS�����������������ҳ����ʾ
	 */
	public static function VEscapeSlashesHtml($vValue, $aExclude=array());
	/**
	 * �����ı� ��ʾ
	 */
	public static function VEscapeMultiline($vValue, $aExclude=array());
}

class Ko_View_Escape
{
	/**
	 * @return string
	 */
	public static function SAddSlashes($sInput)
	{
		return addslashes(self::_SForbidScript($sInput));
	}

	/**
	 * @return string
	 */
	public static function SAddSlashesHtml($sInput)
	{
		return self::SAddSlashes(htmlspecialchars($sInput));
	}

	/**
	 * @return string
	 */
	public static function SMultiline($sInput)
	{
		return nl2br(htmlspecialchars($sInput));
	}
	
	/**
	 * @return string
	 */
	public static function SEscapeJson($vValue)
	{
		return json_encode($vValue);
	}
	
	/**
	 * @return string
	 */
	public static function SEscapeEditor($sValue, $sTextType='html')
	{
		if($sTextType == 'plain')
  		{
			$sValue = str_replace(array('&quot;', '&lt;', '&gt;', '&amp;'), array('"', '<', '>', '&'),
						str_replace(array('<br />', '<br/>'), array('', ''), $sValue));
		}
		return str_replace(array("\n", "\r"), array('\\n', ''), self::SAddSlashes($sValue));
	}

	public static function VEscapeHtml($vValue, $aExclude=array())
	{
		return self::_VEscape('', $vValue, 'htmlspecialchars', $aExclude);
	}
	
	public static function VEscapeSlashes($vValue, $aExclude=array())
	{
		return self::_VEscape('', $vValue, array('self', 'SAddSlashes'), $aExclude);
	}
	
	public static function VEscapeSlashesHtml($vValue, $aExclude=array())
	{
		return self::_VEscape('', $vValue, array('self', 'SAddSlashesHtml'), $aExclude);
	}
	
	public static function VEscapeMultiline($vValue, $aExclude=array())
	{
		return self::_VEscape('', $vValue, array('self', 'SMultiline'), $aExclude);
	}
	
	private static function _VEscape($sKey, $vInput, $fnEscape, $aExclude)
	{
		if (in_array($sKey, $aExclude))
		{
			return $vInput;
		}
		if (is_array($vInput))
		{
			foreach ($vInput as $k => $v)
			{
				$vInput[$k] = self::_VEscape($k, $v, $fnEscape, $aExclude);
			}
			return $vInput;
		}
		return call_user_func($fnEscape, $vInput);
	}

	private static function _SForbidScript($sText)
	{
		$sText = str_replace("\r", '', $sText);
		return preg_replace('/script/i', ' script ', $sText);
	}
}
