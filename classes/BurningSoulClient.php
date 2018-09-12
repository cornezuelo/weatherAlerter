<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BurningSoulClient
 *
 * @author IBERLEY\oaviles
 */
class BurningSoulClient {
	private $endpoint = 'http://api.burningsoul.in';
	
	private function call($uri) {	
		$cliente = curl_init();
		curl_setopt($cliente, CURLOPT_URL, $uri);
		curl_setopt($cliente, CURLOPT_HEADER, 0);
		curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true); 

		$contenido = curl_exec($cliente);
		curl_close($cliente);	
		return json_decode($contenido,true);
	}	
	
	public function moon($timestamp=false) {
		$uri = $this->getEndpoint().'/moon';
		if (is_numeric($timestamp)) {
			$uri .= '/'.$timestamp;
		}
		return $this->call($uri);
	}
	
	function getEndpoint() {
		return $this->endpoint;
	}

	function setEndpoint($endpoint) {
		$this->endpoint = $endpoint;
	}
}
