<?php
/**
 * Created by PhpStorm.
 * User: Kuldeepsinh
 * Date: 8/26/2016
 * Time: 1:14 AM
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class GoogleAuthLib {

    public $core;
    public $adExchangeBuyer;
    public $adExchangeSeller;
    public $admin;
    public $adSense;
    public $adSenseHost;
    public $analytics;
    public $Blogger;
    public $client;
    public $plus;
    public $drive;
    public $YouTube;

    /**
     * Initialize google client
     */
    public function __construct()
    {
        $this->core = &get_instance();
        $this->client = new Google_Client();
        $this->client->setApplicationName($this->core->config->item('GOOGLE_APP_NAME'));
        $this->client->setClientId($this->core->config->item('GOOGLE_ID'));
        $this->client->setClientSecret($this->core->config->item('GOOGLE_SECRET'));
        $this->client->setRedirectUri(site_url($this->core->config->item('GOOGLE_CALLBACK_URL')));
        $this->client->addScope(array(Google_Service_Plus::USERINFO_PROFILE,Google_Service_Plus::USERINFO_EMAIL,Google_Service_Drive::DRIVE));

        /** @var  set access type as offline */
        /** @var  force approval */
        $this->client->setAccessType('offline');
        $this->client->setApprovalPrompt('force');
    }

    /**
     * get google adExchangebuyer object
     */
    public function Service_AdExchangeBuyer()
    {
        $this->adExchangeBuyer  = new Google_Service_AdExchangeBuyer($this->client);
    }

    /**
     * get google adExchangeseller object
     */
    public function Service_AdExchangeSeller()
    {
        $this->adExchangeSeller =  new Google_Service_AdExchangeSeller($this->client);
    }

    /**
     * get google Blogger object
     */
    public function Service_Blogger()
    {
        $this->Blogger = new Google_Service_Blogger($this->client);
    }

    /**
     * get google plus object
     */
    public function Service_Plus(){
        $this->plus = new Google_Service_Plus($this->client);
    }

    /**
     * get google drive object
     */
    public function Service_Drive(){
        $this->drive = new Google_Service_Drive($this->client);
    }

    /**
     * get google Youtube object
     */
    public function Service_YouTube()
    {
        $this->YouTube = new Google_Service_YouTube($this->client);
    }
}