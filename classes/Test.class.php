<?php
	class Test {
		#muutujad e properties
		private $secret_value=1234567890;
		public $nonsecret_value=10;
		private $received_secret;
		
		#funktsioonid e methods
		function __construct($received_value)
		{
			echo " Klass hakkas tööle! ";
			$this->received_secret = $this->secret_value*$received_value;
			echo " saabunud väärtuse korrutis salajase arvuga on: ".$this->received_secret;
			$this->multiply();
		}
		
		function __destruct()
		{
			echo " Klass lõpetas! ";
		}
		
		private function multiply()
		{
			echo " Teine korrutis on: ".$this->secret_value*$this->nonsecret_value;
		}
		
		public function reveal()
		{
			echo " Salajane muutuja väärtus on: ".$this->secret_value;
		}
	}
?>