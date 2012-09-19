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

class VideoCanvas_Cutter
{
	public function cut(array $images)
	{
		$image1 = new Imagick($images[0]);
		$w = $image1->getImageWidth();
		$h = $image1->getImageHeight();
		$hparts = floor($w/BLOCK);
		$vparts = floor($h/BLOCK);

		$tiles = array();
		$current = 0;
		$tot = $hparts * $vparts * count($images);

		$meta = array();

		foreach ($images as $nframe => $frame) {
			$image = new imagick($frame);
			for ($row = 0; $row < $vparts; $row++) {
				for ($col = 0; $col < $hparts; $col++) {
					$IM = $image->clone();
					$IM->cropImage(BLOCK, BLOCK, BLOCK*$col, BLOCK*$row);

					$comparisons = array();
					$hasTile = false;
					$candidates = array();
					
					if ($nframe > 0) {
						
						$p = $hparts*$row + $col;
						$inThisPositionFromPreviousFrame = $meta[$nframe-1][$p]['tile'];
						
						$luckyTile = $tiles[$inThisPositionFromPreviousFrame];
						$result = $IM->compareImages($luckyTile, Imagick::METRIC_MEANSQUAREERROR);
						
						$equals = $result[1] == 0.0;
						if ($equals) {
							// this is an awesome proof that P=NP :-)
							$hasTile = true;

							$meta[$nframe][] = array(
								'l' => BLOCK*$col,
								't' => BLOCK*$row,
								'tile' => $inThisPositionFromPreviousFrame
							);
						}
					}
					
					if (!$hasTile) {
						foreach ($tiles as $pos => $tile) {
							try {
								$result = $IM->compareImages($tile, Imagick::METRIC_MEANSQUAREERROR);
							}
							catch (Exception $e) {
								$tile->writeImages("/tmp/tile.png", true);
								$IM->writeImages("/tmp/im.png", true);
								die($e->getMessage());
							}

							$comparisons[$pos] = $result;

							$equals = $result[1] == 0.0;
							if ($equals) {
								$hasTile = true;

								$meta[$nframe][] = array(
									'l' => BLOCK*$col,
									't' => BLOCK*$row,
									'tile' => $pos
								);

								break;
							} else {
								if ($result[1] < 0.000001) {
									$candidates[$pos] = $result[1];
								}
							}
						}
					}

					if (!$hasTile) {

						if (count($candidates) == 0) {
							$meta[$nframe][] = array(
								'l' => BLOCK*$col,
								't' => BLOCK*$row,
								'tile' => count($tiles)
							);

							$tiles[] = $IM;
						} else {
							$min = 1;
							$minPosition = 0;
							foreach ($candidates as $pos => $error) {
								if ($error < $min) {
									$min = $error;
									$minPosition = $pos;
								}
							}

							$meta[$nframe][] = array(
								'l' => BLOCK*$col,
								't' => BLOCK*$row,
								'tile' => $pos
							);
						}

					}

					$current++;
					if ($current%10==0 || $current==$tot) {
						echo "Processing tile $current/$tot ...\n";
					}
				}
			}

		}
		
		return array(
			'tiles' => $tiles,
			'meta' => $meta,
			'width' => $hparts*BLOCK,
			'height' => $vparts*BLOCK,
		);
	}
}