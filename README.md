# Digaku API - PHP Library

Live example http://dwi.web.id/digaku

### AVAILABLE API
* User info (Authentic API)
* User streams (Authentic API)

### UPCOMING
All of Digaku.com API

### How to (sorry my english is very bad):

* Create application on http://auth.digaku.com/ui/client/create and get your client id and client secret
* Download digaku.php from this page and put on your app folder, if you use Codeigniter, put on [YOUR_APP]/application/libraries/
* Create your application

### SINGLE SIGN ON (you may need to read this http://oauth.net/)
After authorize proccess, Digaku.com API will be redirect to your callback url given and add a CODE variable on url (variable GET). And then assign the CODE variable to setcode() function object, example: $digaku->setcode($_GET['code']). The CODE variable is required to get access token, so may be you will need to save it on session, cookie, or database. Without the CODE variable, you can't request access token, and without access token you can't access API data. For complete guide, please see live example http://dwi.web.id/digaku and see the example bellow:

	<?php
	//start session to save callback $_GET['code'] from digaku after authorize
	session_start();

	//call digaku.php
	include('digaku.php');

	//make configuration
	$config['client_id'] = ''; //required
	$config['client_secret'] = ''; //required
	$config['language'] = ''; //optional id_ID or en_US, default id_ID
	// Assign class object with configuration to $digaku
	// Use $this->load->library('digaku', $config); for Codeigniter
	$digaku = new digaku($config);

	// Your application callback
	$url_callback = '';

	// Check $_GET['code'] and save it in session
	if (isset($_GET['code'])) {
		if (isset($_SESSION['digaku_sess_code'])) {
			unset($_SESSION['digaku_sess_code']);
			$_SESSION['digaku_sess_code'] = $_GET['code'];
		} else {
			$_SESSION['digaku_sess_code'] = $_GET['code'];
		}
	}

	// if the session with $_GET['code'] content available, set the value to get access token
	if (isset($_SESSION['digaku_sess_code'])) {
		$digaku->setcode($_SESSION['digaku_sess_code']);
	}

	// Clear tokken if any request via $_GET['keluar']
	if (isset($_GET['keluar'])) {
		$digaku->logout();
		session_destroy();
	}

	// Check for login state and get the API data
	if ($digaku->checklogin()) {
		//link logout
		echo '<a href="'.$url_callback.'?keluar=out">Logout</a><br />';
	
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
		echo '<a href="'.$digaku->authorize($url_callback).'">Login</a>';
	}
	?>