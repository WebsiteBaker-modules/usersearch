<?php

/* 
 * DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS HEADER.
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * tool.php
 *
 * @category     WBModule
 * @package      WBModule_user_search
 * @author       Bernd Michna, Daniel Fankhauser (badknight)
 * @copyright    Bernd Michna
 * @license      http://www.gnu.org/licenses/gpl.html   GPL License
 * @version      0.32b
 * @lastmodified $Date: 25.05.2013  15:33 $
 */

// prevent this file from being accessed directly
if (defined('WB_PATH') == false) {
    exit("Cannot access this file directly");
}


/**
 * Load Language file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

// include Website Baker module functions introduced with WB 2.7
require_once(WB_PATH . '/framework/module.functions.php');

/**
 * include jscalendar-setup
 */
$jscal_use_time = true; // whether to use a clock, too
require_once(WB_PATH."/include/jscalendar/wb-setup.php");
require_once(WB_PATH."/include/jscalendar/jscalendar-functions.php");

/**
 * Create Twig template object and configure it
 */
// register Twig shipped with Usersearch if not already done by the WB core (included since WB 2.8.3 #1688)
if (! class_exists('Twig_Autoloader')) {
    require_once ('lib/Twig/Autoloader.php');
    Twig_Autoloader::register();
}
$loader = new Twig_Loader_Filesystem(dirname(__FILE__) . '/templates');
$twig = new Twig_Environment($loader, array(
    'autoescape' => false,
    'cache' => false,
    'strict_variables' => false,
    'debug' => false,
));
   /**
    * Make WB_URL and language file available in Twig template
    * Access via: {{ lang.KEY }}, {{ WB_URL }}
    */
    $data = array();
    $data['WB_URL'] = WB_URL;
    $data['ADMIN_URL'] = ADMIN_URL;
    $data['FTAN'] = $admin->getFTAN();
    foreach ($MOD_USER_SEARCH as $key => $value) {
        $data['lang'][$key] = $value;
    }
 
if (!isset($_POST['search'])) { 
	// Show usersearch form
    
    $tpl = $twig->loadTemplate('usersearch_form.twig');

	// System variables 
    $data["JSCAL_IFFORMAT"] = $jscal_ifformat;
    $data["JSCAL_FIRSTDAY"] = $jscal_firstday;
    $data["JSCAL_TODAY"] = $jscal_today;
 	
	// Show all groups

	// access database and obtain groups
	$results = $database->query("SELECT * FROM `".TABLE_PREFIX."groups`"); 
	if ($results && $results->numRows() > 0) {
        $data["groups"] = array();
		while($row = $results->fetchRow()) {
            $tmp = array();
            $tmp["GROUP_ID"] = $row['group_id'];
            $tmp["GROUP_NAME"] = $row['name'];
            $data["groups"][] = $tmp;
		}
	}
} else {	
	// Show the result form	
	//initialising the default values
	$wheretosearch = 0;
	$item = "";
	$g_id = "";
	$resultquery = "";
	$displaysearchfield = "";
	
	//xmlstoresearch will contain the query in xml format (to store and load search)
	$xmlstoresearch = "";
	
	// if a search term was entered make the regexp and build the "LIKE" query	
	if ($_POST['begriff']!=""){
		if (isset($_POST['username']))	$wheretosearch += 1; 
		if (isset($_POST['realname']))  $wheretosearch += 2;
		if (isset($_POST['email']))		$wheretosearch += 4;
		
		//$item = $admin->add_slashes($admin->get_post('begriff'));
		
		$xmlstoresearch .= "<searchterm>".$admin->add_slashes($admin->get_post('begriff'))."</searchterm>";
		$item = str_replace("*", '%', $admin->add_slashes($admin->get_post('begriff')));
		
	
		switch($wheretosearch) {
			case 1:	
				$resultquery = "(username LIKE '".$item."'"; 
				$xmlstoresearch .= "<username>true</username>";
				$displaysearchfield .= $MOD_USER_SEARCH['USER_NAME'];
				break;
			case 2:	
				$resultquery = "(display_name LIKE '".$item."'"; 
				$xmlstoresearch .= "<realname>true</realname>";
				$displaysearchfield .= $MOD_USER_SEARCH['REAL_NAME'];
				break;
			case 3:	
				$resultquery = "(username LIKE '".$item."' OR display_name LIKE '".$item."'"; 
				$xmlstoresearch .= "<username>true</username><realname>true</realname>";				
				$displaysearchfield .= $MOD_USER_SEARCH['USER_NAME'].", ".$MOD_USER_SEARCH['REAL_NAME'];
				break;
			case 4:	
				$resultquery = "(email LIKE '".$item."'";
				$xmlstoresearch .= "<email>true</email>";
				$displaysearchfield .= $MOD_USER_SEARCH['EMAIL'];								
				break;
			case 5:	
				$resultquery = "(username LIKE '".$item."' OR email LIKE '".$item."'"; 
				$xmlstoresearch .= "<username>true</username><email>true</email>";				
				$displaysearchfield .= $MOD_USER_SEARCH['USER_NAME'].", ".$MOD_USER_SEARCH['EMAIL'];								
				break;
			case 6:	
				$resultquery = "(display_name LIKE '".$item."' OR email LIKE '".$item."'"; 
				$xmlstoresearch .= "<realname>true</realname><email>true</email>";
				$displaysearchfield .= $MOD_USER_SEARCH['REAL_NAME'].", ".$MOD_USER_SEARCH['EMAIL'];								
				break;
			case 7:	
				$resultquery = "(username LIKE '".$item."' OR display_name LIKE '".$item."' OR email LIKE '".$item."'"; 
				$xmlstoresearch .= "<username>true</username><realname>true</realname><email>true</email>";				
				$displaysearchfield .= $MOD_USER_SEARCH['USER_NAME'].", ".$MOD_USER_SEARCH['REAL_NAME'].", ".$MOD_USER_SEARCH['EMAIL'];										break;
			default: 
				$resultquery = "";
		}	
	}
	
	
	// if a date was entered modify the sql query	
	if (isset($_POST['comp_date'])&&($_POST['comp_date']!="")){
		if ($resultquery!="") $resultquery .= ") AND";
		$resultquery .= " (login_when";
		$xmlstoresearch .= "<datelastlogin>";
		if ($_POST['datesearch']=="after") {
			$resultquery .= ">";
			$xmlstoresearch .= "<after>true</after>";				
		} else { 
			$resultquery .= "<";
			$xmlstoresearch .= "<before>true</before>";
		}
		// convert date to timestamp format to compare
		$resultquery .= jscalendar_to_timestamp($_POST['comp_date'],TIMEZONE);
		$xmlstoresearch .= "<date>".jscalendar_to_timestamp($_POST['comp_date'],TIMEZONE)."</date>";
		$xmlstoresearch .= "</datelastlogin>";
    }
	
	// if a group was choosen modify the sql query
	if (isset($_POST['groups'])&&($_POST['groups']!="-1")) {
		$g_id = $_POST['groups'];
		if ($resultquery!="") $resultquery .= ") AND";
		$g_id = str_replace(",", '%', $g_id);
		$resultquery .= " (groups_id LIKE '%$g_id%'";
		$xmlstoresearch .= "<idgroup>".$g_id."</idgroup>";
		
		// retrieve the group name
        $sql = "SELECT name from `".TABLE_PREFIX."groups` WHERE group_id = ".(int)$g_id;
        $query_group = $database->query($sql);
        if($query_group->numRows() > 0) {
            $group_name = $query_group->fetchRow();
            $data["GROUP_NAME"] = $group_name["name"];
            $xmlstoresearch .= "<groupname>".$group_name["name"]."</groupname>";
        }

    }
		
	$results = $database->query("SELECT * FROM `".TABLE_PREFIX."users` WHERE ".$resultquery.")");

    $tpl = $twig->loadTemplate('usersearch_result.twig');
	
	if ($database->is_error()) { 
		// echo '<p style="color: #CC0000;">'.$database->get_error().'</p>';
	} else {	

		if ($wheretosearch != 0) {
            // display if a search term was entered
            $data["post"]["BEGRIFF"] = $admin->add_slashes($admin->get_post('begriff'));
            $data["DISPLAYSEARCHFIELD"] = $displaysearchfield;
        }
		 
		if (isset($_POST['comp_date'])&&($_POST['comp_date']!="")) {
			// display if a date of last login was entered		
            $data["post"]["datesearch"] = $_POST['datesearch'];
            $data["post"]["comp_date"] = $_POST['comp_date'];
		}
		
		if ($results->numRows() > 0) {
			$rowcnt = 1;
            $data["result"] = array();
			while($d = $results->fetchRow()) {
				if ($d['login_when'] == 0) {
					$lastlogin = '-';
                } else {
                    $lastlogin = gmdate(DATE_FORMAT." ".TIME_FORMAT, $d['login_when'] + TIMEZONE);
                }
                $tmp = array();
                $tmp["USER_ID"] = $d['user_id'];			
                $tmp["USER_ID_KEY"] = $admin->getIDKEY($d['user_id']);		
                $tmp["USERNAME"] = $d['username'];			
                $tmp["DISPLAYNAME"] = $d['display_name'];			
                $tmp["EMAIL"] = $d['email'];			
                $tmp["LASTLOGIN"] = $lastlogin;			
                $tmp["LAST_IP"] = $d['login_ip'];			
                $tmp["DAYS_INACTIVE"] = ($d['login_when'] == 0) ? '-' : round((time() - (int) $d['login_when']) / (3600 * 24));
                $data["result"][] = $tmp;
            }
        }	
    }
}
// ouput the final template

$tpl->display($data);
?>