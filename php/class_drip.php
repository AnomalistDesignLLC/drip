<?php
/**
 * Drip Helper class
 *
 * @package    drip
 * @author     Matthew Aaron Raymer (matthew.raymer@anomalistdesign.com)
 * @copyright  Copyright (c) RapidSeedbox.com
 * @license    UNLICENSED
 * @version    0.0.1
 * @link       http://www.rapidseedbox.com/
 */

/**
 * A PHP class for accessing Drip Email Marketing Automation API
 *
 * The class is empty for the sake of this example.
 *
 * @package whmcs_drip
 * @subpackage Common
 * @author     Matthew Raymer <matthew.raymer@anomalistdesign.com>
 */
class Subscribers {

    private $_api_key = "";
    private $_base_url = "https://api.getdrip.com/v2/";
    private $_command = "";
    private $_verb = "POST";
    private $_request = "";
    private $_response = "";
    private $_isDelete = false;
    private $_error = false;
    private $_message = "";
    private $_last_request = "";

    /**
     * Drip Subscribers properties
     */

    private $_account_id = "";
    private $_email = "";
    private $_id = "";  // Drip id
    private $_user_id = ""; // WHMCS id
    private $_time_zone = "";  // DO this one
    private $_ip_address = ""; // DO this one
    private $_custom_fields = array();  // do not do this one yet
    private $_tags = array();
    private $_remove_tags = array();  // do not do this one yet
    private $_prospect = true; // DO this one
    private $_base_lead_score = 30; // DO this one

    /**
     * Class constructor for Drip API class
     *
     * @param string $key Drip API key
     */
    public function Subscribers( $key ) {
	$this->_api_key = $key;
    }

    /**
     *
     *  Getters and setters
     *
     */
    public function SetAccountId($accountId){
	$this->_account_id=$accountId;
    }

    /**
     * Get a the subscriber Id
     */
    public function GetId() {
	return $this->_id;
    }

    /**
     * Get an tags
     */
    public function GetTags() {
	return $this->_tags;
    }

    /**
     * Add a tag
     */
    public function AddTag( $tag ) {
	$this->_tags[] = $tag;
    }

    /**
     * Get an UserId
     */
    public function GetUserId() {
	return $this->_user_id;
    }

    /**
     * Set a UserId
     */
    public function SetUserId( $userId ) {
	$this->_user_id = $userId;
    }


    /**
     * Set an Id
     */
    public function SetId( $id ) {
	$this->_id = $id;
    }

    public function ListAll() {

	$this->_verb = "GET";
	$this->_command = $this->_account_id . "/subscribers";
	$this->request();
	return $this->_response;

    }

    /**
     * Set email address
     *
     * @param string email
     */
    public function SetEmail( $email ) {
	$this->_email = $email;
    }

    /**
     * Get email address
     *
     * @return string
     */
    public function GetEmail() {
	return $this->_email;
    }

    /**
     * Get the Drip Account Id
     *
     * @return string|account Id
     */
    public function GetAccountId() {
	return $this->_account_id;
    }

    /**
     * Get the Drip Account Id
     *
     * @param string|account Id
     */
    public function SetAccount( $accoundId ) {
	$this->_account_id = $accountId;
    }

    /**
     * Public methods
     *
     */

    /**
     * Fetch a subscriber record by Drip Id
     */
    public function FetchById( $id ) {
	$this->_id = $id;
	$this->_command = $this->_account_id . "/subscribers/" . $id;
	$this->_verb = "GET";
	$this->request();
	$o = json_decode($this->_response);
	if ( isset( $o->errors ) ) {
	  echo var_export( $o->errors, true ) . "\n";
	  $this->_error = true;
	  $this->_message = $o->errors[0]->message;
	} else {
	  echo var_export( $o ) . "\n";
	  $this->_email = $o->subscribers[0]->email;
	}
    }

    /**
     * Fetch a subscriber record by Email address
     */
    public function FetchByEmail( $email ) {
	$this->_email = $email;
	$this->_command = $this->_account_id . "/subscribers/" . $email;
	$this->_verb = "GET";
	$this->request();
	$o = json_decode($this->_response);
	if ( isset( $o->errors ) ) {
	  echo var_export( $o->errors, true ) . "\n";
	  $this->_error = true;
	  $this->_message = $o->errors[0]->message;
	} else {
	  echo var_export( $o ) . "\n";
	  $this->_id = $o->subscribers[0]->id;
	}
    }

    /**
     * Delete a subscriber record by Drip Id
     */
    public function DeleteById( $id ) {
	$this->_command = $this->_account_id . "/subscribers/" . $id;
	$this->_verb = "DELETE";
	$this->request();
	return $this->_response;
    }

    /**
     * Delete a subscriber record by Email address
     */
    public function DeleteByEmail( $email ) {
	$this->_command = $this->_account_id . "/subscribers/" . $email;
	$this->_verb = "DELETE";
	$this->request();
	return $this->_response;
    }

    /**
     * Unsubscribe a subscriber from a campain or campaigns
     * @param string $token - id or email
     * @param string $campaign_id - all or a specific campaign
     */
    public function Unsubscribe( $token, $campaign_id ) {
    }

    /**
     *
     */
    public function Delete( $token ) {
    }

    /**
     *
     */
    public function BatchCreateUpdate( $subscribers ) {
    }

    /**
     *  Property Getters and Setters
     */
    public function GetLastRequest() {
	return $this->_last_request;
    }

    /**
     * Retrieve the data from the response from ProfitWell
     *
     * @return string|data return from request to ProfitWell
     */
    public function GetResponse() {
	return $this->_response;
    }

    /**
     * Flag if there is an error
     *
     * @return boolean|was there an error
     */
    public function HadError() {
	return $this->_error;
    }

    /**
     * Error message
     *
     * @return string|a description of the error
     */
    public function Error() {
	return $this->_message;
    }

    /**
     * request
     */
    private function request() {
	$ch = curl_init();
	$url = $this->_base_url . $this->_command;
	curl_setopt( $ch, CURLOPT_URL, $url );
	if ( $this->_verb == "DELETE" ) curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "DELETE" );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, TRUE );
	curl_setopt( $ch, CURLOPT_HEADER, FALSE );
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
	    'Accept: application/vnd.api+json',
	    'Content-Type: application/vnd.api+json',
	]);
	if ( $this->_verb == "POST" ) {
	    curl_setopt( $ch, CURLOPT_POST, TRUE );
	    curl_setopt( $ch, CURLOPT_POSTFIELDS, $this->_request );
	}
	curl_setopt($ch, CURLOPT_USERPWD, $this->_api_key . ":");

	$response = curl_exec( $ch );
	curl_close( $ch );
	$this->_response = $response;
    }

    /**
     * MakeRequest
     */
    private function MakeRequest() {
	$args=array();
	$args["email"] = $this->_email;
	if ( count($this->_tags) > 0 ) {
	    $args["tags"] = $this->_tags;
	}
	if ( strlen( $this->_user_id ) > 0 ) {
	    $args["user_id"] = $this->_user_id;
	}
	if ( strlen( $this->_id ) > 0 ) {
	    $args["id"] = $this->_id;
	}
	$this->_request = '{ "subscribers" : [' . json_encode($args) . ']}';
    }

    /**
     * Create or Update a subscriber
     *
     * @param string|id of the subscriber
     * @return string|response from request
     */
    public function Update( $id ) {
	$this->_id = $id;
	$this->MakeRequest();
	$this->_command = $this->_account_id . "/subscribers";
	$this->_verb = "POST";
	$this->request();
	return $this->_response;
    }

    /**
     * Create
     *
     * @param string|$email email address of the subscriber
     * @return string|response from request
     */
    public function Create( $email ) {

	$this->_email = $email;
	$this->MakeRequest();
	$this->_command = $this->_account_id . "/subscribers";
	$this->_verb = "POST";
	$this->request();
	return $this->_response;
    }

    /**
     * Set the time zone of subscriber
     */
    public function time_zone( $time_zone ) {
	$args=array();
	$args["time_zone"] = $this->time_zone;
	if ( strlen( $this->time_zone ) > 0 ) {
	    $args["user_id"] = $this->_user_id;
	}
	$this->_request = '{ "subscribers" : [' . json_encode($args) . ']}';
    }
}
?>
