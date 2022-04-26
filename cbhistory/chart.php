<?PHP

//	chart.php

 	require_once 'chartDraw.php';

class Chart
{
	private $chartDraw;
	private $width = -1;
	private $height = -1;
	private $fontSize = -1;
	private $minX = -1;
	private $maxX = -1;
	private $minY = -1;
	private $maxY = -1;
	private $rightTextLengthX; // length most right X label
	private $maxTextLengthY; // max length Y label

	public function setPixelSize($aWidth, $aHeight, $aFontSize) 
	{
		$this->width = $aWidth;
		$this->height = $aHeight;
		$this->fontSize = $aFontSize;
	}

	public function setMinMaxX($aMinX, $aMaxX, $aRightTextLengthX) 
	{
		$this->minX = $aMinX;
		$this->maxX = $aMaxX;
		$this->rightTextLengthX = $aRightTextLengthX;
	}
	
	public function setMinMaxY($aMinY, $aMaxY) 
	{
		$this->minY = $aMinY;
		$this->maxY = $aMaxY;
		// if $aMinY negative, the text length can be longer than $aMaxY
		$this->maxTextLengthY = max(strlen(strval($aMinY)), strlen(strval($aMaxY)));
	}
	
	public function addNewLine($aRed, $aGreen, $aBlue) 
	{
		if ($this->chartDraw == null) { // create at first call of this function
			$errorMessage = $this->validateParameters();
			if ($errorMessage != '') {
				return $errorMessage;
			}
			$this->chartDraw = new ChartDraw($this->width, $this->height, $this->fontSize
				, $this->maxTextLengthY, $this->maxTextLengthY);
		}
		
		$this->chartDraw->addNewLine($aRed, $aGreen, $aBlue);
		return '';
	}
	
	public function setPoint($aX, $aY, $aXLabelText) 
	{
		$errorMessage = $this->validateXY($aX, $aY);
		if ($errorMessage != '') {
			return $errorMessage;
		}
		
		$xPixel = round(($aX - $this->minX) * $this->width / ($this->maxX - $this->minX));
		$yPixel = round(($aY - $this->minY) * $this->height / ($this->maxY - $this->minY));

		$this->chartDraw->set($xPixel, $yPixel, $aXLabelText);
		return '';
	}
	
	public function show($aLabelCount) 
	{
		$this->setYLabels($aLabelCount);
		$this->chartDraw->show();
	}
	
// -----------------------------------------------------------------------

	private function setYLabels($aLabelCount) 
	{
		$aLabelCount = $aLabelCount - 1; // the for loop needs the # intervals
		for ($i = 0; $i <= $aLabelCount; $i++) {
			$yPixel = round($i / $aLabelCount * $this->height);
			$text = round($this->minY + $i / $aLabelCount * ($this->maxY - $this->minY));
			$this->chartDraw->setLabelY($yPixel, strval($text));
		}
	}

	private function validateParameters()
	{
		if ($this->width <= 0) {
			return '$width: '.$this->width.' must be positive';
		}
		if ($this->height <= 0) {
			return '$height: '.$this->height.' must be positive';
		}
		if ($this->fontSize < 1 || $this->fontSize > 5) {
			return '$fontSize: '.$this->fontSize.' must 1, 2, 3, 4 or 5';
		}			
		if ($this->minX < 0) {
			return '$minX: '.$this->minX.' may not be negative';
		}
		if ($this->maxX <= $this->minX) {
			return '$maxX '.$this->maxX.' must be greater than $minX '.$this->minX;
		}
		if ($this->maxY <= $this->minY) {
			return '$maxY '.$this->maxY.' must be greater than $minY '.$this->minY;
		}
		return '';
	}

	private function validateXY($aX, $aY)
	{
		if ($aX < $this->minX) {
			return 'aX '.$aX.' may not be less than minX: '.$this->minX;
		}
		if ($aX > $this->maxX) {
			return 'aX '.$aX.' may not be greater than maxX: '.$this->maxX;
		}
		if ($aY < $this->minY) {
			return 'aY '.$aY.' may not be less than minY: '.$this->minY;
		}
		if ($aY > $this->maxY) {
			return 'aY '.$aY.' may not be greater than maxY: '.$this->maxY;
		}
		return '';
	}
	
}

// eof
