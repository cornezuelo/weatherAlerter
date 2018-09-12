<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OpenWeatherClient
 *
 * @author IBERLEY\oaviles
 */
class OpenWeatherClient {	
	private $endpoint = 'http://api.openweathermap.org/data/2.5/forecast';
	private $APIKey;
	
	function __construct($APIKey) {
		$this->APIKey = $APIKey;
	}
	
	function call($data=[]) {
		global $openWeatherMapAPIKey;
		$data['APPID'] = $this->getAPIKey();
		$data = http_build_query($data);
		$cliente = curl_init();
		curl_setopt($cliente, CURLOPT_URL, $this->getEndpoint()."?".$data);	
		curl_setopt($cliente, CURLOPT_HEADER, 0);
		curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true); 

		$contenido = curl_exec($cliente);
		curl_close($cliente);	
		return json_decode($contenido,true);
	}
	
	function getEndpoint() {
		return $this->endpoint;
	}

	function getAPIKey() {
		return $this->APIKey;
	}

	function setEndpoint($endpoint) {
		$this->endpoint = $endpoint;
	}

	function setAPIKey($APIKey) {
		$this->APIKey = $APIKey;
	}
}
