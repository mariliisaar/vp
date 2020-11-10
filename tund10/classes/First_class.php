<?php
	class First{
		// Muutujad: klassis omadused (properties)
		private $mybusiness;
		public $everybodysbusiness;
		
		function __construct($limit) {
			$this->mybusiness = mt_rand(0, $limit);
			$this->everybodysbusiness = mt_rand(0, 100);
			echo "Arvude korrutis on: " .$this->mybusiness * $this->everybodysbusiness;
			$this->tellSecret();
		} // constructor lõpeb
		
		function __destruct() {
			echo "Nägite hulka mõttetut infot! <br/>";
		}
		
		// Funktsioonid: klassis meetodid (methods)
		private function tellSecret() {
			echo " Saladusi võib ka välja rääkida!";
		}
		
		public function tellMe() {
			echo " Salajane arv on: " .$this->mybusiness ."<br/>";
		}
		
	} // class lõpeb