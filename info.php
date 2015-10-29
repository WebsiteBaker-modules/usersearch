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
 * info.php
 *
 * @category     WBModule
 * @package      WBModule_user_search
 * @author       Bernd Michna, Daniel Fankhauser (badknight)
 * @copyright    Bernd Michna
 * @license      http://www.gnu.org/licenses/gpl.html   GPL License
 * @version      0.32b
 * @lastmodified $Date: 25.05.2013  13:39 $
 
 -----------------------------------------------------------------------------------------
 v0.33 (Badknight; 25.05.2013)
 	+ fixed PHP warnings
 	+ changed template engine to twig
 	+ fixed bug edit user know work- FTAN and IDKEY was missing
 	+ changed version to 0.33
 	~ need to Update DE and Nl language files.
 
 v0.32b (Quinto; 04.04.2009)
 	+ fixed important bug in group selection now it works in ALL the cases even when with combined groups
 	+ source code refining now it use template files (stored in the htt directory), source code is easier to read now.
 	+ added 'days since last connection' and 'Ip address' in results
 	~ we still need to Update DE and Nl language files.
 
 v0.31b (Quinto; 07.04.2009)
 	+ cosmetic changes to consistency with the Website Baker Admin UI
 	+ some source code refining and added some little thing to help in the future
 	  for example to allow to store the search.
 	+ added functionality to search users according the last login date
 	+ changed the way to work now you can mix all the search criteria (searchterm, date and group)
 	+ added FR language support (support the new functionalities)
 	+ EN language updated to support new functionalities
 	~ now we need to Update DE and Nl language files.
  	
 v0.30 (BerndJM; 24.03.2009)
 	+ added functionality to search als users that are members of a given group 
 
 v0.21 (BerndJM, Aldus, Bramus; 06.03.2009)
 	+ added NL language support (thanks to Bramus)
 	~ some source code refining (tanks to Aldus)
 	
 v0.20  (BerndJM; 02.03.2009)
 	+ added language support (DE and EN only)
   ~ some minor cosmetic changes to provide it as an admin-tool
    
 v0.10  (BerndJM; 28.02.2009)
   + initial release of the module (for internal use only)
 -----------------------------------------------------------------------------------------
*/

$module_directory = 'user_search';
$module_name = 'User search';
$module_function = 'tool';
$module_version = '0.33';
$module_platform = '2.8.3';
$module_author = 'Bernd Michna, Daniel Fankhauser (badknight)';
$module_license = 'GNU General Public License';
$module_description = 'This module provides a search function for the user management.';
$module_guid = 'EE87F4AD-F675-4E44-81EE-91B8D491977B';

?>