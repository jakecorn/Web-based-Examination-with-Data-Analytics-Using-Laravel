<?php
	namespace Modules\Utilitize;
	error_reporting(0);
	use Session;
	class Util
	{
		
		function __construct()
		{
			
		}


		static public function randPassword()
		{
			$range = range(0, 10);
			$password="";
			for ($i=0; $i <6 ; $i++) { 
					
				$password.=$range[rand(0,9)];
			}

			return $password;

		}

		static public function get_session($name){
			return Session::get($name);
		}

		static public function set_session($name,$value){
			return Session::put($name,$value);
		}

		static public function flush(){
			Session::flush();
		}
	}

?>