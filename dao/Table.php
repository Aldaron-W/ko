<?php
/**
 * Table
 *
 * @package ko\dao
 * @author zhangchu
 */

/**
 * ���ݱ������ӿ�
 */
interface IKo_Dao_Table
{
	/**
	 * @return int
	 */
	public function iTableCount();
	/**
	 * @return Ko_Data_Mysql
	 */
	public function oConnectDB($no);
	/**
	 * @return string
	 */
	public function sGetRealTableName($no);
	public function vDoFetchSelect($sSql, $fnCallback);
}
