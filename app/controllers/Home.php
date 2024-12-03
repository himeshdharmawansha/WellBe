<?php 

/**
 * home class
 */
class Home extends Controller
{

	public function index()
	{

		$user = new User;
		$arr['username'] = "www@gmail.com";
		$arr['password'] = "12345";
		$arr2['name'] = "D";
		//$result = $user->where($arr);

		$arr1['name'] = "Miller";
		$arr1['age'] = 45;
		//$user -> insert($arr1);

		$id = 10;
		//$result = $user -> delete($id);

		$arr3['name'] = "Dha";
		$arr3['age'] = 32; 
		$id = 5;
		//$result = $user -> update($id, $arr3);

		$result = $user -> findAll();

		//echo $result;
		$user = $_SESSION['USER'];
		echo $user->password;

		$this->view('home');
	}

}
