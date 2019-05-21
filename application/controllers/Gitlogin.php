<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gitlogin extends CI_Controller {

public function __construct()
{
	parent::__construct();

	// $this->load->library('github');
}
	
public function index(){
	$userData = array();
	
}
public function login(){
	
    $client_id = "2d264d61e985d4809d43";
    $redirect_url = "https://demo.tradefinex.org/publicv/bond_create";
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $url = 'https://github.com/login/oauth/authorize?client_id='. $client_id. "&redirect_url=".$redirect_url."&scope=user";
        header("location: $url");
	}
	
}

function fetchData()
{
    $client_id = "2d264d61e985d4809d43";
    $redirect_url = "https://demo.tradefinex.org/publicv/bond_create";
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['code'])) {
            $code = $_GET['code'];
            $post = http_build_query(array(
                    'client_id' => $client_id,
                    'redirect_url' => $redirect_url,
                    'client_secret' => 'e0141fa85195f84b400314b7cc67ef9cb4d65b7c',
                    'code' => $code,

                ));
        }

        $access_data = file_get_contents("https://github.com/login/oauth/access_token?". $post);
       
        $exploded1 = explode('access_token=', $access_data);
        $exploded2 = explode('&scope=user', $exploded1[1]);

        $access_token = $exploded2[0];
        

        $opts = [ 'http' => [
                        'method' => 'GET',
                        'header' => [ 'User-Agent: PHP']
                    ]
        ];

        //fetching user data
        $url = "https://api.github.com/user?access_token=$access_token";
        $context = stream_context_create($opts);
        $data = file_get_contents($url, false, $context);

    
        $user_data = json_decode($data, true);
        $username = $user_data['login'];


        //fetching email data
        $url1 = "https://api.github.com/user/emails?access_token=$access_token";
        $emails = file_get_contents($url1, false, $context);
        $emails = json_decode($emails, true);

        $email = $emails[0];
       
        $userPayload = [
            'username' => $username,
            'email' => $email,
            'fetched from' => "github"
        ];
        $_SESSION['payload'] = $userPayload;
        $_SESSION['user'] = $username;

		return $userPayload;
		

    }
    else {
        die('error');
    }
    
}
}