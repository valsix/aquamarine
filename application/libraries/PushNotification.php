<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'kloader.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Kauth
 *
 * @author user
 */

define( 'API_ACCESS_KEY', 'AAAAdFNwuT0:APA91bEQp9GX1GFUkd2WoJ0-vYo5ctADPwzPApdHMsKsc7cG_Yz781LFvkdTplgbt8gKVwhTJQWbUDQDlReTMxHvvzyZMi20R1YlQM8mfEVuUskhkPJwd-vvX0w4W-qPJImdv6UKzK1D');

class PushNotification{
	var $tokenFirebase; 
	var $type;
	var $id;
	var $jenis;
	var $title;
	var $body;
	
    /******************** CONSTRUCTOR **************************************/
    function PushNotification(){
		 $this->emptyProps();
    }

    /******************** METHODS ************************************/
    /** Empty the properties **/
    function emptyProps(){
		$this->tokenFirebase = "";
		$this->type = "";
		$this->id = "";
		$this->jenis = "";
		$this->title = "";
		$this->body = "";

    }

    /** Verify user login. True when login is valid**/
    function send_notification($tokenFirebase, $type, $id, $jenis, $title, $body, $url){
    	// echo 'Hello';

		#prep the bundle
		$msg = array
		(
			'body' 	=> $body,
			'title'	=> $title,
			'sound' => 'default',
			'icon'	=>'default'
		);
		
		$data = array
		(
			'type'	=> $type,
			'id'	=> $id,
			'jenis'	=> $jenis,
			'gambar' => $url
		);

		$fields = array
		(
			'to'			=> $tokenFirebase,
			'notification'	=> $msg,
			'data'			=> $data
		);
		
		$headers = array
		(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
		);
		
		#Send Reponse To FireBase Server	
		
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		//echo $id.$tokenFirebase." -> ".$result;

		$this->hasil = $result;

		curl_close( $ch );

	}
	function send_notification_v2($data){
	
		
		$fields = array
		(
			'to'			=> $data['to'],
			'data'			=> $data['data']
		);
		
		$headers = array
		(
			'Authorization: key=' . API_ACCESS_KEY,
			'Content-Type: application/json'
		);
		
		#Send Reponse To FireBase Server	
		
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		//echo $result;
		
		$this->hasil = $result;
		
		curl_close( $ch );
	
	}
			   
}
	
  /***** INSTANTIATE THE GLOBAL OBJECT */
  $pushNotification = new PushNotification();

?>
