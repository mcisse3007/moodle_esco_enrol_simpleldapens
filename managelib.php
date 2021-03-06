<?php

function getDefaultsValuesForUser($enrol_simpleldap, $ldapselector, $nb_filter){
	global $USER;
	$username = $USER->username;
	$attributes = array();
	for ($i = 1; $i <= $nb_filter ; $i++){
	  array_push($attributes, $enrol_simpleldap->getConfig('filter'.$i.'_default'));  
	}

	//FIXME externaliser
	array_push($attributes, "entpersonstructrattach");
	array_push($attributes, "entpersonfonctions");
	array_push($attributes, "entauxensclasses");
	array_push($attributes, "entauxensgroupes");
	
	return $ldapselector->get_userAttributeMultiple($username,$attributes);
}

function createListFilter($i, $enrol_simpleldap, $ldapselector, $userValues, $codes){
	
	$listFilter = array();
	$code = $codes[$i];

	$ldapFilter = $enrol_simpleldap->getConfig('filter'.$i.'_list_filter');

	for ($j = 1; $j <= $i ; $j++){
    	if ($codes[$j] != ''){
      		$ldapFilter = str_replace("{CODE".$j."}", $codes[$j], $ldapFilter);
    	}
  	}
  	$listFilterTmp = $ldapselector->get_listFilter($i,$ldapFilter);
  	$listFilter = $ldapselector->matchLDAPValues($listFilterTmp, $userValues, $i); 

  	if (count($listFilter) == 0 ){
	    $code = '';
	}
    
	//If there is no value for this filter, we will choose one.
	if ($code == ''){
    
		//Init with the first available data.
    	if ($enrol_simpleldap->getConfig('filter'.$i.'_mandatory') && $code == ''){
    		$keys = array_keys($listFilter);
      		$code = array_shift($keys);
    	} 

    	$defaultAttr = $enrol_simpleldap->getConfig('filter'.$i.'_default');
    	if ($defaultAttr != null) {
        	if ( $listFilter != null && array_key_exists($defaultAttr, $listFilter) && $listFilter[$defaultAttr] != null ){
        		$code = $defaultAttr;
        	} else if ( array_key_exists($defaultAttr, $userValues)  && $userValues[$defaultAttr] != null ){
	        	$defaultValue = $userValues[$defaultAttr];
	        	if (array_key_exists($defaultAttr, $listFilter)  && $listFilter[$defaultValue] != '' ){
	        		$code = $defaultValue;
	        	}
        	}
    	}
  	}

  	return [$listFilter, $code] ;
}
?>