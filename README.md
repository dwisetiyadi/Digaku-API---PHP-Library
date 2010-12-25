Live example http://dwi.web.id/digaku

How to (sorry my english is very bad):
--------------------------------------
* Create application on http://auth.digaku.com/ui/client/create and get your client id and client secret
* Download digaku.php from this page and put on your app folder, if you use Codeigniter, put on [YOUR_APP]/application/libraries/
* Create your application code like this:

$config['client_id'] = ''; //required
$config['client_secret'] = ''; //required
$config['language'] = ''; //optional, default id_ID (for indonesian) or you can change to en_US (for english)
$digaku = new digaku($config); //use $this->load->library('digaku', $config) for Codeigniter framework

SINGLE SIGN ON (you may need to read this http://oauth.net/)

After authorize proccess, Digaku.com API will be redirect to your callback url given and add a CODE variable on url (variable GET). And then assign the CODE variable to setcode() function object, example: $digaku->setcode($_GET['code']). The CODE variable is required to get access token, so may be you will need to save it on session, cookie, or database. Without the CODE variable, you can't request access token, and without access token you can't access API data. For complete guide, please see live example http://dwi.web.id/digaku and see example.php on this package.

AVAILABLE API
-------------
* User info (Authentic API)
* User streams (Authentic API)

UPCOMING
--------
All of Digaku.com API