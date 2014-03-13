<?php
/**
 * IPLocator ���ļ�ʹ��gb���뱣�棬����ʹ��utf-8���뱣��
 *
 * @package ko
 * @subpackage data
 * @author zhangchu
 */

//include_once('../ko.class.php');

/**
 * ��װ UObject �Ľӿ�
 */
interface IKo_Data_IPLocator
{
	public static function OInstance();
	public function sGetLocation($sIp);
	public function aGetLocations($aIp);
}

class Ko_Data_IPLocator extends Ko_Data_KProxy implements IKo_Data_IPLocator
{
	const PROXY_ARRMAX = 1000;
	private static $s_OInstance;

	private static $s_aChineseMainlandPrefix = array(
		'����' => 1, '����' => 1, '����' => 1, '����' => 1, '�㶫' => 1,
		'����' => 1, '����' => 1, '����' => 1, '�ӱ�' => 1, '����' => 1,
		'����' => 1, '����' => 1, '����' => 1, '����' => 1, '����' => 1,
		'����' => 1, '����' => 1, '����' => 1, '����' => 1, '�ຣ' => 1,
		'ɽ��' => 1, 'ɽ��' => 1, '����' => 1, '�Ϻ�' => 1, '�Ĵ�' => 1,
		'���' => 1, '����' => 1, '�½�' => 1, '����' => 1, '�㽭' => 1,
		'����' => 1, '�й�' => 1, 'ȫ��' => 1, '����' => 1, '����' => 1,
		'�о�' => 1, '����' => 1, '����' => 1, '�п�' => 1, '�滢' => 1,
		'��ͨ' => 1, '����' => 1, '����' => 1, 'CNNI' => 1, 'UCWE' => 1,
	);
	private static $s_aHkmotwPrefix = array(
		'����' => 1, '���' => 1, '̨��' => 1,
	);
	
	const RANGE_C1_FOREIGN = 0;
	const RANGE_C1_CHINESEMAINLAND = 1;
	const RANGE_C1_HKMOTW = 2;
	
	protected function __construct ()
	{
		KO_DEBUG >= 6 && Ko_Tool_Debug::VAddTmpLog('data/IPLocator', '__construct');
		parent::__construct('IPLocator');
	}

	public static function OInstance()
	{
		if (empty(self::$s_OInstance))
		{
			self::$s_OInstance = new self();
		}
		return self::$s_OInstance;
	}

	public function sGetLocation($sIp)
	{
		$oCtx = $this->_aGetCacheContext(86400);
		$aPara = array(
			'ip' => strval($sIp),
			);
		$ret = $this->_oProxy->invoke('getLocation', $aPara, $oCtx);
		return $ret['location'];
	}
	
	public function aGetLocations($aIp)
	{
		$oCtx = $this->_aGetCacheContext(86400);
		$aIp = array_map('strval', $aIp);
		$len = count($aIp);
		$ret = array();
		for ($i=0; $i<$len; $i+=self::PROXY_ARRMAX)
		{
			$aPara = array(
				'ips' => array_slice($aIp, $i, self::PROXY_ARRMAX),
				);
			$tmp = $this->_oProxy->invoke('getLocations', $aPara, $oCtx);
			$ret = array_merge($ret, $tmp['locations']);
		}
		return $ret;
	}
	
	public function iGetRangeC1($sIp)
	{
		$location = $this->sGetLocation($sIp);
		$head = substr($location, 0, 4);
		if (isset(self::$s_aChineseMainlandPrefix[$head]))
		{
			return self::RANGE_C1_CHINESEMAINLAND;
		}
		else if (isset(self::$s_aHkmotwPrefix[$head]))
		{
			return self::RANGE_C1_HKMOTW;
		}
		return self::RANGE_C1_FOREIGN;
	}
}

/*
$ip = Ko_Data_IPLocator::OInstance();

$ret = $ip->sGetLocation('119.161.156.146');
var_dump($ret);

$ret = $ip->aGetLocations(array('192.168.0.1', '119.161.156.146', '192.168.0.1', '114.113.225.190'));
var_dump($ret);

*/