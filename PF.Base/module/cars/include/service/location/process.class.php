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
class Cars_Service_Location_Process extends Phpfox_Service
{
	private $_aCountries = array();
	
	/**
	 * Class constructor
	 */	
	public function __construct()
	{
        $this->database()->query("SET NAMES UTF8");
        $this->_sTable = Phpfox::getT('cars_location');
	}

    public function add($aVals)
    {
        if (empty($aVals['country_iso']) || empty($aVals['name']))
        {
            return Phpfox_Error::set(Phpfox::getPhrase('admincp.all_fields_are_required'));
        }

        if (strlen($aVals['country_iso']) > 3)
        {
            return Phpfox_Error::set(Phpfox::getPhrase('cars.iso_can_only_contain_3_characters'));
        }

        $iIsCountry = $this->database()->select('COUNT(*)')
            ->from(Phpfox::getT('country'))
            ->where('country_iso = \'' . $this->database()->escape($aVals['country_iso']) . '\'')
            ->execute('getField');

        if ($iIsCountry)
        {
            return Phpfox_Error::set(Phpfox::getPhrase('admincp.the_iso_is_already_in_use'));
        }

        $iOrdering = $this->database()->select('COUNT(*)')->from(Phpfox::getT('cars_location'))->execute('getField');
        $this->database()->insert(Phpfox::getT('cars_location'), array(
                'country_iso' => $aVals['country_iso'],
                'name' => $this->preParse()->clean($aVals['name']),
                'ordering' => ($iOrdering + 1)
            )
        );

        $this->cache()->remove('cars_location', 'substr');

        return true;
    }

    public function delete($sIso)
    {
        $this->database()->delete(Phpfox::getT('cars_location'), 'country_iso = \'' . $this->database()->escape($sIso) . '\'');

        $this->cache()->remove('cars_location', 'substr');

        return true;
    }

    public function update($sIso, $aVals)
    {
        if (!isset($aVals['country_iso']) || !isset($aVals['name']))
        {
            return Phpfox_Error::set(Phpfox::getPhrase('admincp.all_fields_are_required'));
        }

        if (strlen($aVals['country_iso']) > 3)
        {
            return Phpfox_Error::set(Phpfox::getPhrase('cars.iso_can_only_contain_3_characters'));
        }

        $this->database()->update(Phpfox::getT('cars_location'), array(
                'country_iso' => $aVals['country_iso'],
                'name' => $this->preParse()->clean($aVals['name'])
            ), 'country_iso = \'' . $this->database()->escape($sIso) . '\''
        );

        $this->cache()->remove('cars_location', 'substr');

        return true;
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
		if ($sPlugin = Phpfox_Plugin::get('cars.service_location_process__call'))
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
