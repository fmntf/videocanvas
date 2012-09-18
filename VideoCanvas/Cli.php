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

class VideoCanvas_Cli
{
	const VERSION = '0.1';

	public function run()
	{
		$arguments = $_SERVER['argv'];
		
		if ($_SERVER['argc'] != 4 || $arguments[1] != '--convert') {
			echo $this->usage();
			exit(1);
		}
		
		$videoPath = $arguments[2];
		$destinationPath = $arguments[3];
		
		$extractor = new VideoCanvas_ImagesExtractor();
		$tmpPath = $extractor->extract($videoPath);
		
		echo "Images extracted in " . $tmpPath . PHP_EOL;
		echo "To omit any frame, just delete it." . PHP_EOL;
		echo "When done, press any key to continue.";
		fgets(STDIN);
		
		$images = $extractor->listImages($tmpPath);
		
		$cutter = new VideoCanvas_Cutter();
		$cut = $cutter->cut($images);
		
		$writer = new VideoCanvas_Writer($cut, $destinationPath);
		$writer->createTiles();
		$writer->createHtml();
		
		echo 'DONE! :-)' . PHP_EOL;
	}
	
	/**
	 * Prints usage menu.
	 */
	private function usage()
	{
		return
			"VideoCanvas v" . self::VERSION . PHP_EOL .
			"Usage: VideoCanvas <command>" .
			PHP_EOL . PHP_EOL .
			"Supported commands: ". PHP_EOL .
			"   --convert <video> <dir>     Does everything" . PHP_EOL;
	}

}
