<?php
/*
 * digaku / php-sdk  
 *
 * @package		Digaku API Library
 * @author		Dwi Setiyadi / @dwisetiyadi
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://dwi.web.id
 * @version		1.1
 * Last changed	5 Jan, 2011
 */

// ------------------------------------------------------------------------

/**
 * This class object
 */
class digaku {
	var $cid;
	var $csecret;
	var $ccode;
	var $lang;
	
	/**
	 * Constructor
	 * Configure API setting
	 */
	function digaku($params = array('client_id'=>'', 'client_secret'=>'', 'language'=>'id_ID')) {
		$this->cid = $params['client_id'];
		$this->csecret = $params['client_secret'];
		$this->lang = $params['language'];
	}
	
	// --------------------------------------------------------------------

	/**
	 * Initialize
	 *
	 * Assigns a code value to request access token from Digaku.com
	 *
	 * @access public
	 * @return void
	 */
	function setcode($code = '') {
		$this->ccode = $code;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Autorize
	 *
	 * Authorization process to Digaku.com
	 *
	 * @access public
	 * @return void
	 */
	function authorize($redirect_uri = '') {
		return 'http://auth.digaku.com/authorize?client_id='.$this->cid.'&redirect_uri='.$redirect_uri;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Checking
	 *
	 * Checking any access token for login state
	 *
	 * @access public
	 * @return boolean
	 */
	function checklogin() {
		$access_token_check = simplexml_load_string($this->getcontent('http://auth.digaku.com/access_token?code='.$this->ccode.'&client_secret='.$this->csecret));
		if ($access_token_check === FALSE) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * Access Token
	 *
	 * Get access token value, have two value.
	 * To access it, use:
	 * [CLASS OBJECT]->accesstoken()->token for access token
	 * [CLASS OBJECT]->accesstoken()->refresh for refresh token code, this is use for refreshing a new access token
	 *
	 * @access public
	 * @parametersboject token refresh
	 * @return string
	 */
	function accesstoken() {
		$request_accesstoken = $this->getcontent('http://auth.digaku.com/access_token?code='.$this->ccode.'&client_secret='.$this->csecret);
		$request_accesstoken = str_replace('access_token=', '', $request_accesstoken);
		$arr = explode('&refresh_token=', $request_accesstoken);
		$data->token = $arr[0];
		$data->refresh = $arr[1];
		return $data;
	}
	
	// --------------------------------------------------------------------

	/**
	 * Clear Token
	 *
	 * Delete exist token on Digaku.com.
	 *
	 * @access public
	 * @return boolean
	 */
	function logout($access_token_saved = '') {
		if ($access_token_saved == '') {
			$get_token = $this->accesstoken()->token;
		} else {
			$get_token = $access_token_saved;
		}
		
		$clear_request = simplexml_load_string($this->getcontent('http://auth.digaku.com/clear_token?access_token='.$get_token));
		if ($clear_request === FALSE) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	// --------------------------------------------------------------------

	/**
	 * User
	 *
	 * Get user API data. How to call it:
	 * [CLASS OBJECT]->user('info') for user detail information
	 * [CLASS OBJECT]->user('streams') for user streams
	 *
	 * @access public
	 * @parameter 1 info streams
	 * @parameter 2 saved access token
	 * @return object
	 */
	function user($params = '', $access_token_saved = '') {
		if ($access_token_saved == '') {
			$get_token = $this->accesstoken()->token;
		} else {
			$get_token = $access_token_saved;
		}
		
		if ($params == 'info') {
			$api_data = simplexml_load_string($this->getcontent('http://api.digaku.com/my/info?access_token='.$get_token.'&rf=json&itl='.$this->lang));
			if ($api_data === FALSE) {
				return json_decode($this->getcontent('http://api.digaku.com/my/info?access_token='.$get_token.'&rf=json&itl='.$this->lang));
			} else {
				echo '<h1>Error Access Token!</h1>';
			}
		}
		if ($params == 'streams') {
			$api_data = simplexml_load_string($this->getcontent('http://api.digaku.com/my/info?access_token='.$get_token.'&rf=json&itl='.$this->lang));
			if ($api_data === FALSE) {
				return json_decode($this->getcontent('http://api.digaku.com/my/streams?access_token='.$get_token.'&rf=json&itl='.$this->lang));
			} else {
				echo '<h1>Error Access Token!</h1>';
			}
		}
		
		echo '<h1>Unavailable API data for user '.$params.'</h1>';
	}
	
	// --------------------------------------------------------------------

	/**
	 * Page content
	 *
	 * Get return value from Digaku.com page
	 *
	 * @access private
	 * @parameters url
	 * @return string
	 */
	private function getcontent($url = '') {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}
?>