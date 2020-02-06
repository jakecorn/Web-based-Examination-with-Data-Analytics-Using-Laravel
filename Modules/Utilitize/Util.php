<?php
	namespace Modules\Utilitize;
	use Illuminate\Support\Str;
	use Illuminate\Http\UploadedFile;
	use Illuminate\Support\Facades\Storage;

	error_reporting(1);
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

		static function uploadPhoto($uploadedFile, $folder = null, $disk = 'public', $filename = null){
			$filename = Str::slug($filename);
			$name = !is_null($filename) ? $filename : Str::random(25);
			$file = $uploadedFile->storeAs($folder, $name.'.'.$uploadedFile->getClientOriginalExtension(), $disk);
			return $file;
	   }

		public function deletePhoto($filename = null, $disk = 'public'){
			Storage::disk($disk)->delete($folder.$filename);
		}
	}

?>