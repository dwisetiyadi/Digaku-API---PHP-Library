<?php
/*
 * memulai session (untuk menyimpan code yang dikembalikan Digaku.com setelah authorize.
 * Sifatnya opsional. bisa saja disimpan ke database ataupun cookie
 */
session_start();

//memanggil class digaku.php
include('digaku.php');

//memasukan konfigurasi aplikasi (required)
$config['client_id'] = '4d106ed7fccf2272e1000000';
$config['client_secret'] = 'ebd021b8d3c58dab9fa47d0629144cf93f1de095';

/*
 * Assign class object beserta konfigurasi ke variabel $digaku
 * Jika ingin digunakan pada Codeigniter gunakan $this->load->library('digaku', $config);
 */
$digaku = new digaku($config);

/*
 * Memeriksa apakah nilai code telah dikembalikan (melalui variabel global GET) setelah autorisasi, 
 * jika nilainya ada, disimpan ke session.
 * Pada beberapa framework PHP, secara default biasanya men-disable variabel global GET (contoh Codeigniter),
 * maka pastikan anda meng-enable query string melalui URL pada framework anda.
 */
if (isset($_GET['code'])) {
	if (isset($_SESSION['digaku_sess_code'])) {
		unset($_SESSION['digaku_sess_code']);
		$_SESSION['digaku_sess_code'] = $_GET['code'];
	} else {
		$_SESSION['digaku_sess_code'] = $_GET['code'];
	}
}

//Jika session yg berisi code telah diset sebelumnya, maka value dari code di assign untuk digunakan oleh Class digaku.php
if (isset($_SESSION['digaku_sess_code'])) {
	$digaku->setcode($_SESSION['digaku_sess_code']);
}

//Melakukan clear access token dan membersihkan session jika ada request logout melalui url (variabel global GET)
if (isset($_GET['keluar'])) {
	$digaku->logout();
	session_destroy();
}

//Memeriksa apakah sedang login. Jika login, tampilkan link logout dan API data, jika tidak, tampilkan link login
if ($digaku->checklogin()) {
	//link logout
	echo '<a href="http://dwi.web.id/digaku/index.php?keluar=out">Logout</a><br />';
	
	//access token value
	echo 'Access token: '.$digaku->accesstoken()->token.'<br />';
	
	//refresh access token code
	echo 'Refresh token: '.$digaku->accesstoken()->refresh;
	
	echo '<h3>User Info</h3>';
	echo '<pre>';
	//object user info data
	print_r($digaku->user('info'));
	echo '</pre>';
	
	echo '<h3>Streams</h3>';
	echo '<pre>';
	//object user streams data
	print_r($digaku->user('streams'));
	echo '</pre>';
} else {
	echo '<a href="'.$digaku->authorize('http://dwi.web.id/digaku/index.php').'">Login</a>';
}
?>