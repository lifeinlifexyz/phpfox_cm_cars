<?php
/**
 * [PHPFOX_HEADER]
 */

defined('PHPFOX') or exit('NO DICE!');

/**
 * 
 * 
 * @copyright		[PHPFOX_COPYRIGHT]
 * @author  		CodeMake.Org
 * @package  		Module_Core
 * @version 		$Id: country.class.php 7031 2014-01-08 17:53:30Z Fern $
 */
class Cars_Service_Location_Location extends Phpfox_Service
{
	private $_aCountries = array();
	
	/**
	 * Class constructor
	 */	
	public function __construct()
	{
        $this->database()->query("SET NAMES UTF8");
        $this->_sTable = Phpfox::getT('cars_location');
		$sCachedId = $this->cache()->set('cars_location_' . Phpfox::getLib('locale')->getLangId());
		if (!($this->_aCountries = $this->cache()->get($sCachedId)))
		{
			$aRows = $this->database()->select('c.country_iso, c.name')
				->from($this->_sTable, 'c')				
				->order('c.ordering ASC, c.name ASC')
				->execute('getRows');			
			foreach ($aRows as $aRow)
			{
				$this->_aCountries[$aRow['country_iso']] = $aRow['name'];
			}					
			
			$this->cache()->save($sCachedId, $this->_aCountries);
		}
	}
	
	public function getLocation($sIso)
	{		
		return (isset($this->_aCountries[$sIso]) ? $this->_aCountries[$sIso] : false);
	}
	
	public function get()
	{	
		return $this->_aCountries;
	}
	
	public function getForEdit($sIso = null)
	{
		if ($sIso !== null)
		{
			$this->database()->where('c.country_iso = \'' . $this->database()->escape($sIso) . '\'');
		}
		return $this->database()->select('c.*')
			->from(Phpfox::getT('cars_location'), 'c')
			->group('c.country_iso')
			->order('c.ordering ASC, c.name ASC')
			->execute(($sIso == null ? 'getRows' : 'getRow'));
	}

	/**
	 * If a call is made to an unknown method attempt to connect
	 * it to a specific plug-in with the same name thus allowing 
	 * plug-in developers the ability to extend classes.
	 *
	 * @param string $sMethod is the name of the method
	 * @param array $aArguments is the array of arguments of being passed
	 */
	public function __call($sMethod, $aArguments)
	{
		/**
		 * Check if such a plug-in exists and if it does call it.
		 */
		if ($sPlugin = Phpfox_Plugin::get('cars.service_location_location__call'))
		{
			return eval($sPlugin);
		}
			
		/**
		 * No method or plug-in found we must throw a error.
		 */
		Phpfox_Error::trigger('Call to undefined method ' . __CLASS__ . '::' . $sMethod . '()', E_USER_ERROR);
	}	
}

?>
