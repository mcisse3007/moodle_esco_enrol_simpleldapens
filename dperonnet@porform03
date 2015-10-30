<?php

/**
 * Auxiliary manual user enrolment lib, the main purpose is to lower memory requirements...
 *
 * @package    enrol
 * @subpackage manual
 * @copyright  2010 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/user/selector/lib.php');
require_once($CFG->dirroot . '/enrol/locallib.php');
require_once($CFG->libdir.'/ldaplib.php');


class enrol_simpleldapens_ldapsearch {
    protected $instance;
    protected $ldapconn;    
    
    public function __construct($instance, $ldapconn){
        $this->instance = $instance;
        $this->ldapconn = $ldapconn;
    }
   
    public function getSirenFromUAI($uai){
        $ldapconnection = $this->ldapconn->ldap_connect();
        if (!$ldapconnection) {
                return;
        }
        //$filter= "code1";
        $base_dn = "ou=structures,dc=esco-centre,dc=fr";
        $filter="(ENTStructureUAI=".$uai.")";
        $justThese = ("ENTStructureSIREN");
	$records = ldap_search(
                        $ldapconnection,
                        $base_dn
                        ,$filter, array("ENTStructureSIREN") )
			 
        ;
	
	$entries = ldap_get_entries($ldapconnection, $records);
	$siren="";
	foreach($entries as $ent){
		if (isset($ent["entstructuresiren"])){
			$siren = $ent["entstructuresiren"][0];
		}
	}		
	// free memory 
	unset( $record);
	$this->ldapconn->ldap_close();
		//var_dump( $siren);
		return $siren;
	

    }  
    public function get_listFilter($number,$ldapFilter){
        $filter = 'filter'.$number;
        $list = array();
        
        if ($this->instance->getConfig($filter.'_list_values') != ''){
            $config_values = $this->instance->getConfig($filter.'_list_values');
            $values = explode(";", $config_values);
            foreach ($values as $value){
                $val = explode("#", $value);
                $list[$val[1]] = $val[0];
            }
            
        } else { 
        
	        $ldapconnection = $this->ldapconn->ldap_connect();
	        if (!$ldapconnection) {
	            return;
	        }
	        
	        $keyAttr = $this->instance->getConfig($filter.'_list_code');
	        $labelAttr = $this->instance->getConfig($filter.'_list_label');         
	        
	        $records = $this->ldapconn->ldap_search(
	        		$this->instance->getConfig($filter.'_list_branch'),
	        		$ldapFilter,
	        		array($keyAttr,$labelAttr)        		
	        		);
	        
	        
	        foreach ($records as $record) {
	        	//array_push($flat_records, $records[$c]);
	        	if ($keyAttr == "dn"){
	        		$key = $record[$keyAttr];
                        ////////////////////////////////////////////////
                        // MODIFICATION RECIA | DEBUT | 2013-03-14
                        ////////////////////////////////////////////////
                        // Ancien code :
                        /*
	        	    } else {
	        	    	$key = $record[$keyAttr][0];
	        	    }
			    	if ($labelAttr == "dn"){
			    		$label = $record[$labelAttr];
			    	} else {
			    		$label = $record[$labelAttr][0];					
			    	}
	        	    $list[$key] = $label; 
                        */

                        // Nouveau code :
			} else {
    	                    for ($j = 0; $j < $record[$keyAttr]["count"] ; $j++){
	                    	$key = $record[$keyAttr][$j];
				if($labelAttr == "dn") {
					$label = $record[$labelAttr];
				} else {
	                    	    $label = $record[$labelAttr][$j];
				}
	        	        $list[$key] = $label; 
	                    }
			}
                        ////////////////////////////////////////////////
                        // MODIFICATION RECIA | FIN
                        ////////////////////////////////////////////////
	        }
	        $this->ldapconn->ldap_close();
        }
        
        asort($list);
        return $list;
    }
    
    public function get_userAttribute($username, $attributes){
        $ldapconnection = $this->ldapconn->ldap_connect();
        if (!$ldapconnection) {
            return;
        }
        
        $ldapFilter = "(".$this->instance->getConfig('username_attribute')."=".$username.")";
        
        $records = $this->ldapconn->ldap_search(
                $this->instance->getConfig('branch'),
                $ldapFilter,
                $attributes
        );
         
         
        $list = array();
        foreach ($records as $record) {
            foreach ($attributes as $attribute) {
            	if (array_key_exists($attribute, $record) ){
					$list[$attribute] = $record[$attribute][0];
            	} else {
            		$list[$attribute] = '';	
            	}
                
            }
        }
        $this->ldapconn->ldap_close();
        return $list;
    }
    
    public function get_userAttributeMultiple($username, $attributes){
        $ldapconnection = $this->ldapconn->ldap_connect();
        if (!$ldapconnection) {
            return;
        }
         
        $ldapFilter = "(".$this->instance->getConfig('username_attribute')."=".$username.")";
         
        $records = $this->ldapconn->ldap_search(
                $this->instance->getConfig('branch'),
                $ldapFilter,
                $attributes
        );
    
    
        foreach ($records as $record) {
            foreach ($attributes as $attribute) {
            	if (array_key_exists($attribute, $record) ){
	                $list[$attribute] = $record[$attribute];
	            }
            }
        }
        $this->ldapconn->ldap_close();
        return $list;
    }
    
    public function matchLDAPValues($listFilter, $userValues, $i){
        switch ($i){
            case 1 : return $this->matchLDAPVAlues1($listFilter,$userValues);
            case 2 : return $this->matchLDAPVAlues2($listFilter,$userValues);
            case 3 : return $this->matchLDAPVAlues3($listFilter,$userValues);
            case 4 : return $this->matchLDAPVAlues4($listFilter,$userValues);
        }
        
        return $listTmp;
    }
    
    public function matchLDAPVAlues1($listTmp, $userValues){
    	$liste = array();
    	/*$dn = $userValues["entpersonstructrattach"][0];
    	if ($listTmp[$dn] != ''){
	    	$liste[$dn] = $listTmp[$dn];
    	}*/
    	
        ////////////////////////////////////////////////
        // MODIFICATION RECIA | DEBUT | 2013-03-14
        ////////////////////////////////////////////////
        // Ancien code :
        /*
    	    for ($i = 0; $i < $userValues["entstructuresiren"]["count"] ; $i++){
    	    	$uai = $userValues["entstructuresiren"][$i] ;
    	    	if ($listTmp[$uai] != ''){
    	    		$liste[$uai] = $listTmp[$uai];
    	    	}
    	    }
         
	:*/	
        // Nouveau code :
    	 for ($i = 0; $i < $userValues["escouai"]["count"] ; $i++){
    		$uai = $userValues["escouai"][$i] ;
    		if ($listTmp[$uai] != ''){
    			$liste[$uai] = $listTmp[$uai];
    		}
    	}
        ////////////////////////////////////////////////
        // MODIFICATION RECIA | FIN
        ////////////////////////////////////////////////
        return $liste;
    }
    public function matchLDAPVAlues2($listFilter, $userValues){
        return $listFilter;
    }
    public function matchLDAPVAlues3($listTmp, $userValues){
        ////////////////////////////////////////////////
        // MODIFICATION RECIA | DEBUT | 2013-03-14
        ////////////////////////////////////////////////
        // Ancien code :
        /*
        	$liste = array();
        	 
        	for ($i = 0; $i < $userValues["ismemberof"]["count"] ; $i++){
        		$cn = $userValues["ismemberof"][$i];
        		//echo $cn . "<br/>";
        		if ($listTmp[$cn] != ''){
        			$liste[$cn] = $listTmp[$cn];
        		}
        		
        		$cnBis = str_replace ( "Profs" , "Eleves", $cn );
                if ($listTmp[$cnBis] != ''){
        			$liste[$cnBis] = $listTmp[$cnBis];
        		}
        	}
        */

        // Nouveau code :
        $liste = array();
        for ($i = 0; $i < $userValues["entauxensclasses"]["count"] ; $i++){
        	$classe = $userValues["entauxensclasses"][$i];
        	if ($listTmp[$classe] != ''){
        		$liste[$classe] = $listTmp[$classe];
        	}
        }
        ////////////////////////////////////////////////
        // MODIFICATION RECIA | FIN
        ////////////////////////////////////////////////
	return $liste;
    }
   public function matchLDAPVAlues4($listTmp, $userValues){
        ////////////////////////////////////////////////
        // MODIFICATION RECIA | DEBUT | 2013-03-14
        ////////////////////////////////////////////////
        // Ancien code :
        /*
    		$liste = array();
        	 
        	for ($i = 0; $i < $userValues["ismemberof"]["count"] ; $i++){
        		$cn = $userValues["ismemberof"][$i];
       
        		if ($listTmp[$cn] != ''){
        			$liste[$cn] = $listTmp[$cn];
        		}
        	}
        	return $liste;
        */

        // Nouveau code :
        $liste = array();
        for ($i = 0; $i < $userValues["entauxensgroupes"]["count"] ; $i++){
        	$groupe = $userValues["entauxensgroupes"][$i];
        	if ($listTmp[$groupe] != ''){
        		$liste[$groupe] = $listTmp[$groupe];
        	}
        }
        ////////////////////////////////////////////////
        // MODIFICATION RECIA | FIN
        ////////////////////////////////////////////////
	return $liste;
    }
}

