<?php


if (!function_exists('glc_post')) {
	/**
	 * Sends CURL POST request to service url with some data
	 * @param string $url 
	 * @param array $post 
	 * @return object
	 */
	function glc_post($url, $post) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		$result = curl_exec($curl);
		// if(!$result) die("Connection Failure");
		curl_close($curl);
		$response = json_decode($result);
		return $response;
	}
}