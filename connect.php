<?php 
//include the Facebook PHP SDK
include_once 'src/facebook.php';

//instantiate the Facebook library with the APP ID and APP SECRET
$facebook = new Facebook(array(
		'appId' => '183326848397861',
		'secret' => '58c189ca8c65ff084ea7a8af725dc487',
   		'cookie' => true
	));

//Get the FB UID of the currently logged in user
$user = $facebook->getUser();
 
//if the user has already allowed the application, you'll be able to get his/her FB UID
if($user) {
   //do stuff when already logged in

	//start the session if needed
	if( session_id() ) {
	 
	} else {
	   session_start();
	}
	 
	//get the user's access token
	$access_token = $facebook->getAccessToken();
	 
	//check permissions list
	$permissions_list = $facebook->api(
	   '/me/permissions',
	   'GET',
	   array(
	      'access_token' => $access_token
	   )
	);

} else {
   //if not, let's redirect to the ALLOW page so we can get access
   //Create a login URL using the Facebook library's getLoginUrl() method
   $login_url_params = array(
      'scope' => 'publish_stream,read_stream,offline_access,manage_pages',
      'fbconnect' =>  1,
      'redirect_uri' => 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
   );
   $login_url = $facebook->getLoginUrl($login_url_params);
 
   //redirect to the login URL on facebook
   header("Location: {$login_url}");
   exit();
}

//if the user has allowed all the permissions we need,
//get the information about the pages that he or she managers
$accounts = $facebook->api(
   '/me/accounts',
   'GET',
   array(
      'access_token' => $access_token
   )
);
 
//save the information inside the session
$_SESSION['access_token'] = $access_token;
$_SESSION['accounts'] = $accounts['data'];
//save the first page as the default active page
$_SESSION['active'] = $accounts['data'][0];
 
//redirect to manage.php
header('Location: manage.php');