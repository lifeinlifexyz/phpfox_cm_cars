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
 * @package  		Module_User
 * @version 		$Id: browse.class.php 7167 2014-03-03 18:29:30Z Fern $
 */
class Cars_Service_Browse extends Phpfox_Service
{
	private $_aConditions = array();
	private $_sSort = 'c.time_stamp DESC';
	private $_iPage = 0;
	private $_iLimit = 9;
	private $_aCustom = false;
    private $_bPhoto = false;

	public function __construct()
	{
		Phpfox::getLib('setting')->setParam('cars.dir_photo', Phpfox::getParam('core.dir_pic') . 'cars'.PHPFOX_DS);
		Phpfox::getLib('setting')->setParam('cars.url_photo', Phpfox::getParam('core.url_pic') . 'cars/');
		$this->_sTable = Phpfox::getT('cars');
	}
	
	public function conditions($aConditions)
	{
		$this->_aConditions = $aConditions;
		return $this;
	}
	
	public function sort($sSort)
	{
		$this->_sSort = $sSort;
		
		return $this;
	}
	
	public function page($iPage)
	{
		$this->_iPage = $iPage;
		
		return $this;
	}	

	public function limit($iLimit)
	{
		$this->_iLimit = $iLimit;
		
		return $this;
	}	

	public function custom($mCustom)
	{
		$this->_aCustom = $mCustom;
		
		return $this;
	}

    public function photo($bPhoto){
        $this->_bPhoto = $bPhoto;

        return $this;
    }

    /**
     * This function returns cars_ids for those that match the search by custom fields
     * if the param $bIsCount is true then it only returns the count and not the user_ids
     * @param type $bIsCount
     */
    public function getCustom($bIsCount = true, $iCount = false, $bAddCondition = true)
    {

		if ($bIsCount)
		{
			$aCars = $this->getCustom(false, false, false);
			return count($aCars);
		}

        $aCustomSearch = array();	
        $sSelect = 'c.car_id';
        if ($bIsCount == true)
        {
            $sSelect = ('count(c.car_id)');
        }
        
		if (is_array($this->_aCustom) && !empty($this->_aCustom))
		{

			$sCondition = ' AND (';
			// When searching for more than one custom field searchFields will 
			// return more than one join instruction
			$aAlias = array();
			$aCustomSearch = Phpfox::getService('cars.custom')->searchFields($this->_aCustom);
			$iCustomCnt = 0;
			
			$iJoinsCount = 0;
			$aCarIds = array();
			if (count($aCustomSearch) > 0)
			{			
				$this->database()->select($sSelect . ($bIsCount ? ' as total' : ''))->from($this->_sTable, 'c');
			}
			$aAvoidDups = array();
			
			foreach($aCustomSearch as $iKey => $aSearch)
			{
				if (isset($aAvoidDups[$aSearch['on'] . $aSearch['where']])) 
				{
					unset($aCustomSearch[$iKey]);
					continue;
				}
				
				$aAvoidDups[$aSearch['on'] . $aSearch['where']] = $iKey;
			}
			
			foreach ($aCustomSearch as $iKey => $aSearchParam)
			{
				$iCustomCnt++;
                if ($iCount !== false && is_numeric($iCount) && $iCount > 0)
                {
                    $this->database()->order($this->_sSort)
                        ->limit($this->_iPage, $this->_iLimit, $iCount);
                }
				if (is_array($aSearchParam))
				{
					// The following replacements make sure that the joins are unique by using unique aliases
					$sOldAlias = $aSearchParam['alias'];
					
					$aSearchParam['alias'] = $sNewAlias = $aSearchParam['alias'] . $iCustomCnt;
					
					$sOldOn = $aSearchParam['on'];
					
					$sNewOn = $aSearchParam['on'] = $aCustomSearch[$iKey]['on'] = str_replace($sOldAlias .'.', $sNewAlias .'.', $aSearchParam['on']);					
					
					$aCustomSearch[$iKey]['where'] = str_replace(array('mvc.', $sOldAlias .'.'), $sNewAlias .'.', $aSearchParam['where']);
					
					$sNewWhere = $aCustomSearch[$iKey]['where'];
					
					$sOn = ''.$sNewOn . ' AND ' . $sNewWhere;
					
					$this->database()->join($aSearchParam['table'], $sNewAlias, $sOn);
					$iJoinsCount++;					
					
				} // end of is_array aSearchParam
				else
				{
					$this->database()->join(Phpfox::getT('cars_custom'), 'ccv', $aSearchParam);
					$iJoinsCount++;	
					$sCondition .= ' '.$aSearchParam . ' AND ';					
				}

				if ( $iJoinsCount > 2 && !$bIsCount)
				{
					$aCars = $this->database()->execute('getSlaveRows');
					
					if (empty($aCars) || (isset($aCars[0]['total']) && $aCars[0]['total'] <= 1))
					{
						$aCarIds[0] = 0;
					}
					else
					{
						foreach ($aCars as $aCar)
						{
							$aCarIds[$aCar['car_id']] = $aCar['car_id'];
						}					
					}
					
					$this->database()->select($sSelect)->from(Phpfox::getT('cars'), 'c')->where('c.car_id IN (' . implode(',',$aCarIds) .')');
					$iJoinsCount = 0;
				}
			} // foreach
            if ($bIsCount == true)
            {
                $aCount = $this->database()->execute('getSlaveRows');
                $aCount = array_pop($aCount);

				return (count($aCustomSearch) ? $aCount['total'] : $aCount[$sSelect]);
            }
			if ($iJoinsCount > 0)
			{				
				$aCars = $this->database()->execute('getSlaveRows');
                
				foreach ($aCars as $aCar)
				{
					$aCarIds[$aCar['car_id']] = $aCar['car_id'];
				}				
			}
			if (count($aCarIds))
			{
				$sCondition = 'AND (c.car_id IN (' . implode(',', $aCarIds) .')';
			}
			else if (($iJoinsCount > 0) && (empty($aCars)))
			{
				$sCondition = 'AND (1=2';
				$bNoMatches = true;
			}
			$this->database()->clean();
			
			if ($sCondition != ' AND (' && $bAddCondition)
			{
				$this->_aConditions[] = rtrim($sCondition, ' AND ') . ')';
			}
            
		}

		if ($bAddCondition != true && isset($aCars))
		{
			return $aCars;
		}
        return false;
    }
    
	
	public function get()
	{
		$aReturnCars = array();
		$aCars = array();

        // If there are custom fields to look for we need to know how many users satisfy this criteria
		$iCount = $this->getCustom(true);

        if ($iCount !== false && $iCount < 1)
        {
            $bNoMatches = true;
        }
		else
        {

            $aCars = $this->getCustom(false);
			if ($aCars !== false)
			{
				foreach ($this->_aConditions as $iKey => $sCondition)
				{
					if (preg_match('/c.car_id IN (\([0-9]+\))/', $sCondition, $aMatch) > 0)
					{
						$this->_aConditions[$iKey] = str_replace($aMatch[1], '(' . implode(',', $aCars) . ')', $sCondition);
					}
				}
			}
			
        }
		
		if (!isset($bNoMatches))
		{
			$this->database()->select('COUNT(*)');

			if ($iCount > 0)
			{
				$this->database()->leftjoin(Phpfox::getT('cars_custom'), 'ccv', 'ccv.car_id = c.car_id');
			}

			$iCnt = $this->database()->from($this->_sTable, 'c')
				->where($this->_aConditions)
				->execute('getSlaveField');

		}
		else
		{
			$iCnt = 0;
		}

		if ($iCnt > 0)
		{

			
			if ($iCount > 0)
			{
				$this->database()->leftjoin(Phpfox::getT('cars_custom'), 'ccv', 'ccv.car_id = c.car_id');
			}
			$aReturnCars = $this->database()->select('c.* /*, cp.destination*/, cm.title AS mark_id, cml.title AS model_id')
				->from($this->_sTable, 'c')
                ->leftJoin(Phpfox::getT('cars_mark'), 'cm', 'c.mark_id = cm.mark_id')
                ->leftJoin(Phpfox::getT('cars_model'), 'cml', 'c.model_id = cml.model_id')
				->where($this->_aConditions)
				->order($this->_sSort)
				->limit($this->_iPage, $this->_iLimit, $iCnt)
				->group('c.car_id')
				->execute('getSlaveRows');
            if ($this->_bPhoto && count($aReturnCars)){
                foreach ($aReturnCars as $iIndex=>$aCar){
                    if (!isset($aCar['car_id'])){
                        continue;
                    }
                    $aFields = Phpfox::getService('cars.export')->getCustomFields($aCar['car_id']);
                    $sFields = '';
                    foreach($aFields as $aField){
                        $sFields .= "<div class='info' style='clear: none;padding-bottom: 4px;margin-bottom: 4px;'><div class='info_left'>{$aField['phrase']}: </div><div class='info_right'>{$aField['value']}</div></div>";
                    }
                    if (!empty($sFields)){
                        $aReturnCars[$iIndex]['sFields'] = $sFields;
                    }
                    $aPhotos = Phpfox::getService('cars.cars')->getPhotos($aCar['car_id']);

                    foreach($aPhotos as $aPhoto){
                        if (isset($aPhoto['is_main']) && !empty($aPhoto['is_main'])){
                            $aReturnCars[$iIndex]['destination'] = $aPhoto['destination'];
                        }else{
                            $aReturnCars[$iIndex]['destination'] = $aPhotos[mt_rand(0, sizeof($aPhotos)-1)]['destination'];
                        }
						if (!file_exists(Phpfox::getParam('cars.dir_photo').sprintf($aReturnCars[$iIndex]['destination'], ""))){
							$aReturnCars[$iIndex]['destination'] = 	"no-img.png";
						}
                    }
                }
            }

		}

		return array($iCnt, $aReturnCars);
	}

    public function query()
    {
        if (Phpfox::isModule('like'))
        {
            $this->database()->select('l.like_id as is_liked, adisliked.action_id as is_disliked, ')
                ->leftJoin(Phpfox::getT('like'), 'l', 'l.type_id = "cars" AND l.item_id = c.car_id AND l.user_id = ' . Phpfox::getUserId() . '')
                ->leftJoin(Phpfox::getT('action'), 'adisliked', 'adisliked.action_type_id = 2 AND adisliked.item_id = c.car_id AND adisliked.user_id = ' . Phpfox::getUserId());
        }
/*
        if (Phpfox::getLib('request')->get('mode') == 'edit')
        {
            $this->database()->select('pi.description, ')->leftJoin(Phpfox::getT('photo_info'), 'pi', 'pi.photo_id = photo.photo_id');
        }*/
    }

    public function getQueryJoins($bIsCount = false, $bNoQueryFriend = false)
    {
        if (Phpfox::isModule('friend') && Phpfox::getService('friend')->queryJoin($bNoQueryFriend))
        {
            $this->database()->join(Phpfox::getT('friend'), 'friends', 'friends.user_id = c.user_id AND friends.friend_user_id = ' . Phpfox::getUserId());
        }
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
		if ($sPlugin = Phpfox_Plugin::get('cars.service_browse__call'))
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
