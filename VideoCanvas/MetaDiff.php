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

class VideoCanvas_MetaDiff
{
	public function diff(array $source)
	{
		$frames = count($source);
		$tiles = count($source[0]);
		
		for ($i=$frames-1; $i>0; $i--) {
			
			for ($t=0; $t<$tiles; $t++) {
				$curr = $source[$i][$t]['tile'];
				$prev = $source[$i-1][$t]['tile'];
				
				if ($curr == $prev) {
					unset($source[$i][$t]);
				}
			}
			
		}
		
		return $source;
	}
}
