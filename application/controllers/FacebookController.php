<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Kuldeepsinh
 * Date: 8/6/2016
 * Time: 12:04 AM
 */
//require_once './vendor/autoload.php';

class FacebookController extends CI_Controller
{

    public $fb;

    public function __construct()
    {
        parent::__construct();
        $this->fb = new Facebook\Facebook([
            'app_id' => $this->config->item('FACEBOOK_ID'),
            'app_secret' => $this->config->item('FACEBOOK_SECRET'),
            'default_graph_version' => $this->config->item('GRAPH_version'),
        ]);
    }

    public function index()
    {
        $helper = $this->fb->getRedirectLoginHelper();
        $permissions = ['email', 'user_likes','read_page_mailboxes'];
        $loginUrl = $helper->getLoginUrl(site_url($this->config->item('FACEBOOK_CALLBACK_URL')),$this->config->item('FACEBOOK_PERMISSIONS'));
        echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
    }

    public function callBack()
    {
        $helper = $this->fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (isset($accessToken)) {
            $this->session->set_userdata('facebook_access_token', (string)$accessToken);
            redirect(site_url('facebookController/user'));
        }
    }

    public function user()
    {
        $this->fb->setDefaultAccessToken($this->session->userdata('facebook_access_token'));
        try {
            $response = $this->fb->get('/me');
            $userNode = $response->getGraphUser();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        echo 'Logged in as ' . $userNode->getName();
    }

    public function getAllPost()
    {
        $request = $this->fb->request('GET', '/me/feed', array('access_token' => $this->session->userdata('facebook_access_token')));
        // Send the request to Graph
        try {
            $response = $this->fb->getClient()->sendRequest($request);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        echo "<pre>";
        print_r($response->getBody());
        echo"</pre>";

    }

    public function postStatusPost()
    {
        $request = $this->fb->request('POST', '/me/feed',
            array(
                'access_token' => $this->session->userdata('facebook_access_token'),
                'message'=>'Testing'
            )
        );
        // Send the request to Graph
        try {
            $response = $this->fb->getClient()->sendRequest($request);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        echo "<pre>";
        print_r($response->getBody());
        echo"</pre>";
    }

    public function logout()
    {
        $helper = $this->fb->getRedirectLoginHelper();
        //print_r($helper->getAccessToken());exit;
        $l = $helper->getLogoutUrl($this->session->userdata('facebook_access_token'), site_url(), '&');
        header("Location:" . $l);
        $user_data = $this->session->all_userdata();
        foreach ($user_data as $key => $value) {
            if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
                $this->session->unset_userdata($key);
            }
        }
        print_r($l);
        exit;
        //$logoutUrl = 'https://www.facebook.com/logout.php?next='.site_url().'&access_token='.$helper->getAccessToken();
        //print_r($logoutUrl);exit;
        //$logoutUrl = $this->fb->getLogoutUrl('','','');
        $this->session->sess_destroy();
        //exit;
        header("Location:" . $l);
    }
}