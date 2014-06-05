<?php
/*

	Created by andoitz.com

	Use this script for encrypt your php code
	You can have this class as API in other server
	1. 	Encrypt your php files to cphp
	2. 	Create an API with this class and your encrypted php classes. 
		You can use the php class protector http://www.andoitz.com/proyectos/programacion/34-proyectos/programacion/82-php-class-protector.html
	3.	If you like MVC create a controller with a view and call your classes via your API. You will GET the response functions protected.
	4.	If you dont want touch API server, send your cphp files to de API server. API server will decrypt and execute your functions.

*/
class CriptoPHP{
	private $extension = ".cphp";
	private $key = "example$%&123"; //YOUR PRIVATE KEY
	
	public function __construct(){
		
	}
	private function readFile($script){
		$fp = fopen($script,"r");
		$data = fread($fp,filesize($script));
		fclose($fp);
		return $data;
	}
	public function encryptPHP($script){
		$data = $this->readFile($script.".php");
		$this->saveEncrypt($data,$script);
	}
	public function decryptAndInclude($script){
		$data = $this->decrypt($this->readFile($script.".cphp"));
		eval("?> ".$data." <?php");
	}
	private function saveEncrypt($data,$script){
		$fp = fopen($script.$this->extension,"w");
		fwrite($fp,$this->encrypt($data));
		fclose($fp);
	}
	private function encrypt($data){
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128,$this->key,$data,MCRYPT_MODE_CBC,"\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"));
	}
	private function decrypt($data){
		$decode = base64_decode($data);
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$this->key,$decode,MCRYPT_MODE_CBC,"\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0");
	}
}
//ENCRYPT
$CriptoPHP = new CriptoPHP();
$CriptoPHP->encryptPHP("exampleScript");
//DECRYPT
$CriptoPHP->decryptAndInclude("exampleScript");
?>