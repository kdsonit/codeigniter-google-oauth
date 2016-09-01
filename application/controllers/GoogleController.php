<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Kuldeepsinh
 * Date: 8/7/2016
 * Time: 1:06 PM
 */
class GoogleController extends CI_Controller{

    public $client;
    public $plus;
    public $drive;

    public function __construct()
    {
        parent::__construct();

        $this->client = new Google_Client();
        $this->client->setApplicationName($this->config->item('GOOGLE_APP_NAME'));
        $this->client->setClientId($this->config->item('GOOGLE_ID'));
        $this->client->setClientSecret($this->config->item('GOOGLE_SECRET'));
        $this->client->setRedirectUri(site_url($this->config->item('GOOGLE_CALLBACK_URL')));
        $this->client->addScope(array(Google_Service_Plus::USERINFO_PROFILE,Google_Service_Plus::USERINFO_EMAIL,Google_Service_Drive::DRIVE));

        /** @var  set access type as offline */
        /** @var  force approval */
        $this->client->setAccessType('offline');
        $this->client->setApprovalPrompt('force');

        $this->plus = new Google_Service_Plus($this->client);
        $this->drive = new Google_Service_Drive($this->client);
    }

    /** pre tag view of data
     * @param $data
     */
    public function pre_tag($data)
    {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    /**
     *
     */
    public function index(){
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            redirect(site_url('googleController/user'));
        } else {
            $this->getLogin();
        }
    }

    /**
     * Get Login Redirect
     */
    public function getLogin(){
        header('Location: ' . filter_var($this->client->createAuthUrl(), FILTER_SANITIZE_URL));
    }

    /**
     * Call back function after google.
     */
    public function callBack(){
        if (!isset($_GET['code'])) {
            $this->getLogin();
        } else {
            $this->client->authenticate($_GET['code']);
            $this->session->set_userdata('access_token', $this->client->getAccessToken());
            header('Location: ' . filter_var(site_url('googleController/user'), FILTER_SANITIZE_URL));
        }
    }

    /**
     * User information
     */
    public function user(){
        if ($this->session->userdata('access_token')) {
            $this->client->setAccessToken($this->session->userdata('access_token'));
            $me = $this->plus->people->get("me");
            $this->pre_tag($me);
        }else{
            $this->getLogin();
        }
    }

    public function gDrive(){
        if ($this->session->userdata('access_token') ) {
            $this->client->setAccessToken($this->session->userdata('access_token'));
            $files_list = $this->drive->files->listFiles(array())->getItems();
            $this->pre_tag($files_list);
            //echo json_encode($files_list);
        } else {
            $this->getLogin();
        }
    }

    /**
     * Logout function from site and google.
     */
    public function logout(){
        $user_data = $this->session->all_userdata();
        foreach ($user_data as $key => $value) {
            if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
                $this->session->unset_userdata($key);
            }
        }
        $this->client->revokeToken();
        $this->session->sess_destroy();
        header('Location: https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue='.site_url());
    }

    /**
     *
     */
    public function simpleMap()
    {
        $this->load->view('google/googleMap');
    }

    public function dragableDirectionMap()
    {
        $this->load->view('google/dragableDirectionMap');
    }
}