<?php
/**
 * This file implements the class MainPage.
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
 * MainPage
 *
 * This is the page containing the Boards.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */

class MainPage extends Page
{

	/// True if the image div should be visible
	private $image_div = false;

	/// Boardpanel object
	private $panel;

    /// Specific header content
    private $header_content;

	/// Boards class;
	private $panel_class;

	/// Board header, containing the title and some buttons
	private $header;

	/// Menubar object
	private $accountinfo;

	/// Imagepanel object
	private $image_panel;

	/// Image_panel class
	private $image_panel_class;

	/// Navigation object
	private $navigation;

	/// Infos
	private $infos;

	/**
	 * Creates the page
	 *
	 * @author Thibaud Rohmer
	 */
	public function __construct(){

		try{
			$settings=new Settings();
		}catch(FileException $e){
			// If Accounts File missing... Register !
			$this->header();
			new RegisterPage();
			exit;
		}

		/// Check how to display current file
		if(is_file(CurrentUser::$path)){
			$this->image_panel			=	new ImagePanel(CurrentUser::$path);
			$this->image_panel_class 	=	"image_panel";
			$this->panel				=	new Board(dirname(CurrentUser::$path));
			$this->panel_class			=	"linear_panel";
			$this->header				=	new HeaderBar($this->title,dirname(CurrentUser::$path));
            $this->header_content       =   $this->image_panel->page_header;
		}else{
            $this->image_div            =   false;
			$this->image_panel			=	new ImagePanel();
			$this->image_panel_class	=	"image_panel hidden";
			$this->panel				=	new Board(CurrentUser::$path);
			$this->panel_class			=	"panel";
			$this->header				=	new HeaderBar($this->title,CurrentUser::$path);
            $this->header_content       =   $this->panel->page_header;
		}

		/// Create AccountInfo
		$this->accountinfo 		= 	new AccountInfo();

		/// Navigation
		$this->navigation	=	new Navigation();

		$this->infos 		= 	new Infos();

	}

	/**
	 * Display page on the website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function toHTML(){

		$this->header($this->header_content);
		echo "<body>\n";

		echo "<div id='layout' class='mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header'>\n";

		// Header Bar
		$this->header->toHTML();

		// Drawer
		echo "<div class='mdl-layout__drawer drawer'>\n";
		// Login/Logout
		$this->accountinfo->toHTML();
		//Navigation
		echo "<nav class='mdl-navigation'>\n";
		$this->navigation->toHTML();
		echo "</nav>\n";
		echo "</div>\n";

	// 	echo "<a href='#menuright' class='menuright-link'><span></span></a>";
	// 	// Menu right
	// 	echo "<div id='menuright'>";
		echo "<div class='mdl-layout__drawer-right drawer'>";
		$this->infos->toHTML();
		echo "</div>";
    	// echo "<div class='mdl-layout__obfuscator-right'></div>\n";
	// 	echo "</div>";


	// 	// Main page
	// 	echo "<div id='page'>\n";

	// 	// ImagePanel
	// 	echo "<div id='image_panel' class='$this->image_panel_class'>\n";
	// 	$this->image_panel->toHTML();
	// 	echo "</div>\n";

	// 	// Panel
	// 	echo "<div class='$this->panel_class'>\n";
	// 	$this->panel->toHTML();
	// 	echo "</div>\n";


	// 	echo "</div>\n";
		echo "</div>\n";

		// echo "<script src='inc/js/ui.js'></script>\n";
		echo "<script src='https://code.getmdl.io/1.2.0/material.min.js'></script>\n";
		echo "<script src='src/js/drawer.js'></script>\n";

		echo "</body>";
	}
}

?>
