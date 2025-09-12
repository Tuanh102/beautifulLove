<?php 
	// Thông tin kết nối database
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "webbanhang";
	//Tạo kết nối
	$conn = new mysqli($servername, $username, $password, $dbname);
	//Kiểm tra kết nối
	if($conn){
		mysqli_query($conn, "SET NAMES 'utf8'");
	}else{
		echo 'Kết nối đến database thất bại';
	}
?>