<?php
/**
 * VideoCanvas
 * Copyright (C) 2012 Francesco Montefoschi <francesco.monte@gmail.com>
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
 *
 * @package VideoCanvas
 * @author  Francesco Montefoschi
 * @license http://www.gnu.org/licenses/gpl-3.0.html  GNU GPL 3.0
 */

class VideoCanvas_Writer
{
	private $cut;
	private $destinationPath;
	
	public function __construct(array $cut, $destinationPath)
	{
		$this->cut = $cut;
		$this->destinationPath = $destinationPath;
		
		if (!is_dir($destinationPath)) {
			mkdir($destinationPath, 0777, true);
		}
	}
	
	public function createTiles()
	{
		$tiles = $this->cut['tiles'];
		
		$final = new Imagick();
		$final->newImage(10*BLOCK, BLOCK*ceil(count($tiles)/10), new ImagickPixel('black'));
		$final->setImageFormat('png');

		$i = 0;
		foreach ($tiles as $k => $tile) {
			$final->compositeImage($tile, Imagick::COMPOSITE_DEFAULT, ($i % 10)*BLOCK, floor($i / 10)*BLOCK);
			$i++;
		}

		$final->writeImages($this->destinationPath."/tiles.png", true);
	}
	
	public function createHtml()
	{
		$meta = $this->cut['meta'];
		
		$html = file_get_contents(dirname(__FILE__) . "/template.html");

		$html = str_replace("%%%METADATA%%%", json_encode($meta), $html);
		$html = str_replace("%%%BLOCKSIZE%%%", BLOCK, $html);
		$html = str_replace("%%%IMGWIDTH%%%", $this->cut['width'], $html);
		$html = str_replace("%%%IMGHEIGHT%%%", $this->cut['height'], $html);

		file_put_contents($this->destinationPath."/index.html", $html);
	}
}
