<?php 


class Controller
{

	public function view($name,$active="",$data = [])
	{
		if(!empty($data)){
			extract($data); //anything we extract here will be available for file down here
		}
		$filename = "../app/views/".$name.".view.php";
		if(file_exists($filename))
		{
			require $filename;
		}else{

			$filename = "../app/views/404.view.php";
			require $filename;
		}
	}
}