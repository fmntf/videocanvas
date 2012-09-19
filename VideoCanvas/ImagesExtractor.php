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

class VideoCanvas_ImagesExtractor
{
	public function extract($videoPath)
	{
		return "/tmp/videocanvas-505a406ade34a";
		
		$dir = "/tmp/videocanvas-" . uniqid();
		mkdir($dir);
		
		echo "CALLING ffmpeg ...." . PHP_EOL;
		exec("ffmpeg -i $videoPath $dir/frame%05d.png");
		echo PHP_EOL . PHP_EOL;
		
		return $dir;
	}
	
	public function listImages($dir)
	{
		$images = array();
		foreach (scandir($dir) as $image) {
			if ($image != '.' & $image != '..') {
				$images[] = "$dir/$image";
			}
		}
		
		return $images;
	}
}