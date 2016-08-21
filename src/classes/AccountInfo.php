<?php
/**
 * This file implements the class AccountInfo.
 *
 * PHP versions 4 and 5
 *
 * LICENSE:
 *
 * This file is part of PhotoShow.
 *
 * PhotoShow is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PhotoShow is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PhotoShow.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright 2011 Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */

/**
 * AccountInfo
 *
 * The accountinfo holds some information, depending on the user.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */

class AccountInfo implements HTMLObject{

	/// True if user is logged in
	private $logged_in	= false;

	/// True if user is admin
	private $admin		= false;



	/**
	 * Create accountinfo
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function __construct(){
		$this->logged_in	=	isset(CurrentUser::$account);
		$this->admin		=	CurrentUser::$admin;
	}

	/**
	 * Display AccountInfo on website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function toHTML(){
		echo "<header class='drawer-header mdl-color--primary-dark mdl-color-text--primary-contrast'>\n";
		echo "<span class='mdl-layout-title'>".Settings::$name."</span>\n";
		echo "<div class='account-dropdown'>\n";
		echo "<i class='material-icons'>account_circle</i>";
		if($this->logged_in){
			echo "<span class='account-name'>".htmlentities(CurrentUser::$account->login, ENT_QUOTES ,'UTF-8')."</span>\n";
		}else{
			echo "<span class='account-name'>".Settings::_("accountinfo","guest")."</span>\n";
		}
		echo "<div class='mdl-layout-spacer'></div>\n";
		echo "<button id='accbtn' class='mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon'>\n";
		echo "<i class='material-icons' role='presentation'>arrow_drop_down</i>\n";
		// echo "<span class='visuallyhidden'>Accounts</span>\n";
		echo "</button>\n";
		echo "<ul class='mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect' for='accbtn'>\n";
		if($this->logged_in){
			if($this->admin){
				echo "<li><a class='mdl-menu__item' href='?t=Adm'>".Settings::_("accountinfo","admin")."</a></li>\n";
			}
			echo "<li><a class='mdl-menu__item' href='?t=Acc'>".Settings::_("accountinfo","accounts")."</a></li>\n";
			echo "<li><a class='mdl-menu__item' href='?t=Logout'>".Settings::_("accountinfo","logout")."</a></li>\n";
		}else{
			echo "<li><a class='mdl-menu__item' href='?t=Login'>".Settings::_("accountinfo","login")."</a></li>\n";
			if(!Settings::$noregister){
				echo "<li><a class='mdl-menu__item' href='?t=Reg'>".Settings::_("accountinfo","register")."</a></li>\n";
			}
		}
		echo "</ul>\n";

		echo "</div>\n";
		echo "</header>\n";
	}
}
?>
