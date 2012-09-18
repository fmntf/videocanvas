<?php

class VideoCanvas_MetaDiffTest extends PHPUnit_Framework_TestCase
{
	public function testRemovesEqualsTilesInTheNextFrame()
	{
		$unfiltered = array(
			array(
				array('tile' => 1),
				array('tile' => 2),
				array('tile' => 3),
				array('tile' => 4),
				array('tile' => 5),
				array('tile' => 6),
				array('tile' => 7),
				array('tile' => 8),
				array('tile' => 9),
			),
			array(
				array('tile' => 1),
				array('tile' => 2),
				array('tile' => 3),
				array('tile' => 10),
				array('tile' => 5),
				array('tile' => 6),
				array('tile' => 7),
				array('tile' => 8),
				array('tile' => 9),
			),
			array(
				array('tile' => 1),
				array('tile' => 2),
				array('tile' => 3),
				array('tile' => 10),
				array('tile' => 5),
				array('tile' => 6),
				array('tile' => 7),
				array('tile' => 8),
				array('tile' => 9),
			),
			array(
				array('tile' => 2),
				array('tile' => 3),
				array('tile' => 1),
				array('tile' => 1),
				array('tile' => 5),
				array('tile' => 6),
				array('tile' => 7),
				array('tile' => 8),
				array('tile' => 9),
			),
		);
		
		
		$expected = array(
			array(
				array('tile' => 1),
				array('tile' => 2),
				array('tile' => 3),
				array('tile' => 4),
				array('tile' => 5),
				array('tile' => 6),
				array('tile' => 7),
				array('tile' => 8),
				array('tile' => 9),
			),
			array(
				3 => array('tile' => 10),
			),
			array(
			),
			array(
				array('tile' => 2),
				array('tile' => 3),
				array('tile' => 1),
				array('tile' => 1),
			),
		);
		
		$metadiff = new VideoCanvas_MetaDiff();
		$this->assertSame($expected, $metadiff->diff($unfiltered));
	}
}
