<?php
	class Photoupload{
		private $photoinput; // klassi private muutuja
		private $photofiletype;
		private $mytempimage;
		private $mynewtempimage;
		private $timestamp;
		private $filenameprefix = "vp_";
		public $filename;
		
		function __construct($photoinput) { // konstruktori muutuja
			$this->photoinput = $photoinput;
			// var_dump($photoinput);
		} // construct
		
		function __destruct() {
			if(isset($this->mytempimage)) {
				imagedestroy($this->mytempimage);
			}
		}
		
		public function imageType($photoFileTypes) {
			$notice = null;
			// Kas on pilt
			$check = getimagesize($this->photoinput["tmp_name"]);
			// Kui jah, siis mis tüüpi + loo pildifail
			if(in_array($check["mime"], $photoFileTypes)){
				$this->photofiletype = substr($check["mime"], -3);
				if ($this->photofiletype == "peg") {
					$this->photofiletype = "jpg";
				}
				$notice = 1;
				$this->createImageFromFile();
			} else {
				$notice = 0;
			}
			return $notice;
		}
		
		public function getSize($filesizelimit) {
			$notice = 0;
			$size = $this->photoinput["size"];
			if ($size > $filesizelimit) {
				return $notice;
			}
			else {
				return $size;
			}
		}
		
		public function setFilename($prefix = "", $suffix = "") {
			if ($prefix == "") {
				$prefix = $this->filenameprefix;
			}
			if ($suffix == "") {
				$suffix = $this->photofiletype;
			}
			$this->timestamp = microtime(1) * 10000;
			$this->filename = $prefix .$this->timestamp ."." .$suffix;
			return $this->filename;
		}

		public function file_exists($dir, $name) {
			if(file_exists($dir .$name)) {
				return true;
			}
			else {
				return false;
			}
		}
		
		private function createImageFromFile() {
			if($this->photofiletype == "jpg"){
				$this->mytempimage = imagecreatefromjpeg($this->photoinput["tmp_name"]);
			}
			if($this->photofiletype == "png"){
				$this->mytempimage = imagecreatefrompng($this->photoinput["tmp_name"]);
			}
			if($this->photofiletype == "gif"){
				$this->mytempimage = imagecreatefromgif($this->photoinput["tmp_name"]);
			}
		}
		
		public function resizePhoto($w, $h, $keeporigproportion = true){
			$imagew = imagesx($this->mytempimage);
			$imageh = imagesy($this->mytempimage);
			$neww = $w;
			$newh = $h;
			$cutx = 0;
			$cuty = 0;
			$cutsizew = $imagew;
			$cutsizeh = $imageh;
			
			if($w == $h){
				if($imagew > $imageh){
					$cutsizew = $imageh;
					$cutx = round(($imagew - $cutsizew) / 2);
				} else {
					$cutsizeh = $imagew;
					$cuty = round(($imageh - $cutsizeh) / 2);
				}	
			} elseif($keeporigproportion){//kui tuleb originaaproportsioone säilitada
				if($imagew / $w > $imageh / $h){
					$newh = round($imageh / ($imagew / $w));
				} else {
					$neww = round($imagew / ($imageh / $h));
				}
			} else { //kui on vaja kindlasti etteantud suurust, ehk pisut ka kärpida
				if($imagew / $w < $imageh / $h){
					$cutsizeh = round($imagew / $w * $h);
					$cuty = round(($imageh - $cutsizeh) / 2);
				} else {
					$cutsizew = round($imageh / $h * $w);
					$cutx = round(($imagew - $cutsizew) / 2);
				}
			}
			
			//loome uue ajutise pildiobjekti
			$this->mynewtempimage = imagecreatetruecolor($neww, $newh);
			//kui on läbipaistvusega png pildid, siis on vaja säilitada läbipaistvusega
			imagesavealpha($this->mynewtempimage, true);
			$transcolor = imagecolorallocatealpha($this->mynewtempimage, 0, 0, 0, 127);
			imagefill($this->mynewtempimage, 0, 0, $transcolor);
			imagecopyresampled($this->mynewtempimage, $this->mytempimage, 0, 0, $cutx, $cuty, $neww, $newh, $cutsizew, $cutsizeh);
		}
		
		public function addWatermark($wmfile) {
			if(isset($this->mynewtempimage)) {
				$watermark = imagecreatefrompng($wmfile);
				$wmw = imagesx($watermark);
				$wmh = imagesy($watermark);
				$wmx = imagesx($this->mynewtempimage) - $wmw - 10;
				$wmy = imagesy($this->mynewtempimage) - $wmh - 10;
				// kopeerime vesimärgi vähendatud pildile
				imagecopy($this->mynewtempimage, $watermark, $wmx, $wmy, 0, 0, $wmw, $wmh);
				imagedestroy($watermark);
			}
		}
		
		public function saveimage($target){
			$notice = null;
			if($this->photofiletype == "jpg"){
				if(imagejpeg($this->mynewtempimage, $target, 90)){
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			if($this->photofiletype == "png"){
				if(imagepng($this->mynewtempimage, $target, 6)){
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			if($this->photofiletype == "gif"){
				if(imagegif($this->mynewtempimage, $target)){
					$notice = 1;
				} else {
					$notice = 0;
				}
			}
			imagedestroy($this->mynewtempimage);
			return $notice;
		}
		
		public function saveOriginal($target) {
			if(move_uploaded_file($this->photoinput["tmp_name"], $target)){
				$notice = 1;
			} else {
				$notice = 0;
			}
			return $notice;
		}
		
	} // class lõpeb