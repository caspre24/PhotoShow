<?php
/**
 * This file implements the class Navigation.
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
 * Navigation
 *
 * Creates a menu, by creating Navigation instances for
 * each directory in $dir.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */
class Navigation implements HTMLObject
{
	/// Name of current directory
	public $title;

	/// HTML Class of the div : "selected" or empty
	public $class;

	/// HTML-formatted relative path to file
	private $webdir;

	/// Array of Navigation instances, one per directory inside $dir
	private $items=array();

	/// Relative path to file
	private $path = "";

	/**
	 * Create Navigation
	 *
	 * @param string $dir
	 * @param int $level
	 * @author Thibaud Rohmer
	 */
	public function __construct($dir=null,$level=0){

		/// Init Navigation
		if($dir == null)
			$dir = Settings::$photos_dir;

		/// Check rights
		if(!Judge::view($dir) || Judge::searchDir($dir) == NULL ){
			return;
		}

		if(!CurrentUser::$admin && !CurrentUser::$uploader && sizeof($this->list_files($dir,true,false,true)) == 0){
			return;
		}

		/// Set variables
		$this->title = end(explode('/', $dir));
		$this->webdir= urlencode(File::a2r($dir));
		$this->path  = File::a2r($dir);

		try{

			/// Check if selected dir is in $dir
			File::a2r(CurrentUser::$path,$dir);

			$this->selected			=	true;
			$this->class 			=	"level-$level selected";

		}catch(Exception $e){

			/// Selected dir not in $dir, or nothing is selected
			$this->selected			=	false;
			$this->class 			=	"level-$level";

		}

		/// Create Navigation for each directory
		$subdirs = $this->list_dirs($dir);

		if(Settings::$reverse_menu){
			$subdirs = array_reverse($subdirs);
		}

		foreach($subdirs as $d){
			$this->items[]	=	new Navigation($d,$level+1);
		}
	}

	/**
	 * Display Navigation in website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function toHTML(){
		if(isset($this->webdir) && isset($this->title)){
			echo "<div class='menu_level $this->class'>\n";

			if($this->selected){
				$currentSelected = "currentSelected";
				foreach($this->items as $item){
					if($item->selected){
						$currentSelected="";
					}
				}
				$selected = 'mdl-color-text--black';
			}

			echo "<a class='mdl-navigation__link $selected' href='?f=$this->webdir'>".htmlentities($this->title, ENT_QUOTES ,'UTF-8')."</a>";

			foreach($this->items as $item)
				$item->toHTML();

			echo "</div>\n";
		}
	}

	/**
	 * List directories in $dir, omit hidden directories
	 *
	 * @param string $dir
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public static function list_dirs($dir,$rec=false, $hidden=false){

		/// Directories list
		$list=array();

		/// Check that $dir is a directory, or throw exception
		if(!is_dir($dir))
			throw new Exception("'".$dir."' is not a directory");

		/// Directory content
		$dir_content = scandir($dir);

        if (empty($dir_content)){
            // Directory is empty or no right to read
            return $list;
        }

		/// Check each content
		foreach ($dir_content as $content){

			/// Content isn't hidden and is a directory
			if(	($content[0] != '.' || $hidden) && is_dir($path=$dir."/".$content)){

				/// Add content to list
				$list[]=$path;

				if($rec){
					$list = array_merge($list,Navigation::list_dirs($dir."/".$content,true));
				}

			}

		}

		/// Return directories list
		return $list;
	}

	/**
	 * List files in $dir, omit hidden files
	 *
	 * @param string $dir
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public static function list_files($dir,$rec = false, $hidden = false, $stopatfirst = false){
		/// Directories list
		$list=array();

		/// Check that $dir is a directory, or throw exception
		if(!is_dir($dir))
			throw new Exception("'".$dir."' is not a directory");

		/// Directory content
		$dir_content = scandir($dir);

        if (empty($dir_content)){
            // Directory is empty or no right to read
            return $list;
        }

		/// Check each content
		foreach ($dir_content as $content){

			/// Content isn't hidden and is a file
			if($content[0] != '.' || $hidden){
				if(is_file($path=$dir."/".$content)){
					if(File::Type($path) && (File::Type($path) == "Image" || File::Type($path)=="Video")){
						/// Add content to list
						$list[]=$path;

						/// We found the first one
						if($stopatfirst){
							return $list;
						}
					}
				}else{

					if($rec){
						$list = array_merge($list,Navigation::list_files($dir."/".$content,true));
					}

				}
			}

		}

		/// Return files list
		return $list;
	}

}
?>
