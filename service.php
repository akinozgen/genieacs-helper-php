<?php

// Ğ (⌐■_■)

include __DIR__ . '/parameters.php';
	
class ACSRequest {
		
	const URL = 'http://__GENIEACS_API_URL__:7557';

	/**
	 * curl
	 *
	 * @param string $endpoint
	 * @param string|array $query
	 * @param string|array $body
	 * @param string $type
	 * @param string $mimeType
	 * @param array $rawQuery
	 * @return string
	 */
	static function curl($endpoint = '/', $query = '', $body = '', $type = 'GET', $mimeType = '', $rawQuery = null)
	{
		if (is_array($query)) {
			$query = json_encode($query);
		}
		$query = ($query ? '?query=' . urlencode($query) : '');
		if ($rawQuery) {
			$query = '?' . http_build_query($rawQuery);
		}

		$curl = curl_init();
		$curl_settings = array(
			CURLOPT_URL => self::URL . $endpoint . $query,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $type,
		);

		if ($body) {
			$curl_settings[CURLOPT_POSTFIELDS] = is_array($body) ? json_encode($body) : $body;
		}

		if ($mimeType) {
			$curl_settings[CURLOPT_HTTPHEADER] = array(
				'Content-Type: '. $mimeType // application/json
			);
		}
		curl_setopt_array($curl, $curl_settings);
		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

	/**
	 * getAllDevices
	 *
	 * @return array|bool
	 */
	public static function getAllDevices()
	{
		$response = self::curl('/devices');
		return json_decode($response, true);
	} 

	/**
	 * getDeviceById
	 *
	 * @param int|string $id
	 * @return array|bool
	 */
	public static function getDeviceById($id)
	{
		$query = [ "_id" => $id ];
		$response = self::curl('/devices', json_encode($query));
		
		return json_decode($response, true);
	}

	/**
	 * getDevicesByQuery
	 *
	 * @param array $query
	 * @return array|bool
	 */
	public static function getDevicesByQuery($query)
	{
		$response = self::curl('/devices', json_encode($query));
		return json_decode($response, true);
	}

	/**
	 * getDevicesByTags
	 *
	 * @param array $tags
	 * @return array|bool
	 */
	public static function getDevicesByTags($tags)
	{
		$query = ["_tags" => ['$all' => $tags]];
		$response = self::curl('/devices', json_encode($query));
		return json_decode($response, true);
	}

	/**
	 * deleteDevice
	 *
	 * @param string $id
	 * @return array|bool
	 */
	public static function deleteDevice($id)
	{
		$response = self::curl('/devices/'.$id, '', '', 'DELETE');
		return json_decode($response, true);
	}

	/**
	 * addTag
	 *
	 * @param string $deviceId
	 * @param string $tag
	 * @return array|bool
	 */
	public static function addTag($deviceId, $tag)
	{
		$response = self::curl('/devices/' . $deviceId . '/tags/' . $tag, '', '', 'POST');
		return json_decode($response, true);
	}

	/**
	 * removeTag
	 *
	 * @param string $deviceId
	 * @param string $tag
	 * @return array|bool
	 */
	public static function removeTag($deviceId, $tag)
	{
		$response = self::curl('/devices/' . $deviceId . '/tags/' . $tag, '', '', 'DELETE');
		return json_decode($response, true);
	}

	/**
	 * getFiles
	 *
	 * @return array|bool
	 */
	public static function getFiles()
	{
		$response = self::curl('/files');
		return json_decode($response, true);
	}

	/**
	 * uploadFile
	 *
	 * @param string $path
	 * @param string $filename
	 * @return array|bool
	 */
	public static function uploadFile($path, $filename)
	{
		if (function_exists('curl_file_create')) {
			$body = curl_file_create($path);
		} else {
			$body = '@' . realpath($path);
		}

		$response = self::curl('/files/'.$filename, '', $body, 'PUT');
		return json_decode($response, true);
	}

	/**
	 * deleteFile
	 *
	 * @param string $filename
	 * @return array|bool
	 */
	public static function deleteFile($filename)
	{
		$response = self::curl('/files/'.$filename, '', '', 'DELETE');
		return json_decode($response, true);
	}

	/**
	 * faults
	 *
	 * @param string $deviceId
	 * @return array|bool
	 */
	public static function faults($deviceId)
	{
		// $deviceId
		$response = self::curl('/faults', '', '', 'GET');
		return json_decode($response, true);
	}

	/**
	 * getParameterValues
	 *
	 * @param string $parameters
	 * @param string $deviceId
	 * @return array|bool
	 */
	public static function getParameterValues($parameters, $deviceId)
	{
		$query = ['timeout' => '3000', 'connection_request'];
		$body = [
			"device" => $deviceId,
			"name" => "getParameterValues",
			"parameterNames" => is_array($parameters) ? $parameters : [$parameters]
		]; 

		$response = self::curl('/devices/' . $deviceId . '/tasks', $query, $body, 'POST');
		return json_decode($response, true);
	}

	/**
	 * setParameterValues
	 *
	 * @param array $parameters
	 * @param string $deviceId
	 * @return array|bool
	 */
	public static function setParameterValues($parameters, $deviceId)
	{
		$query = ['timeout' => '3000', 'connection_request'];
		$body = [
			"device" => $deviceId,
			"name" => "setParameterValues",
			"parameterValues" => $parameters
		]; 

		$response = self::curl('/devices/' . $deviceId . '/tasks', $query, $body, 'POST');
		return json_decode($response, true);
	}

	/**
	 * refreshObject
	 *
	 * @param array $parameter
	 * @param string $deviceId
	 * @return array|bool
	 */
	public static function refreshObject($parameter, $deviceId)
	{
		$query = ['timeout' => '3000', 'connection_request'];
		$body = [
			"device" => $deviceId,
			"name" => "refreshObject",
			"objectName" => $parameter
		]; 

		$response = self::curl('/devices/' . $deviceId . '/tasks', $query, $body, 'POST');
		return json_decode($response, true);
	}

	/**
	 * refreshAllObject
	 *
	 * @param string $deviceId
	 * @return array|bool
	 */
	public static function refreshAllObjects($deviceId)
	{
		$query = ['timeout' => '3000', 'connection_request'];
		$body = [
			"device" => $deviceId,
			"name" => "refreshObject",
			"objectName" => ''
		]; 

		$response = self::curl('/devices/' . $deviceId . '/tasks', $query, $body, 'POST');
		return json_decode($response, true);
	}

	/**
	 * reboot
	 *
	 * @param string $deviceId
	 * @return array|bool
	 */
	public static function reboot($deviceId)
	{
		$query = ['timeout' => '3000', 'connection_request'];
		$body = [
			"device" => $deviceId,
			"name" => "reboot"
		]; 

		$response = self::curl('/devices/' . $deviceId . '/tasks', $query, $body, 'POST');
		return json_decode($response, true);
	}

	/**
	 * factoryReset
	 *
	 * @param string $deviceId
	 * @return array|bool
	 */
	public static function factoryReset($deviceId)
	{
		$query = ['timeout' => '3000', 'connection_request'];
		$body = [
			"device" => $deviceId,
			"name" => "factoryReset"
		]; 

		$response = self::curl('/devices/' . $deviceId . '/tasks', $query, $body, 'POST');
		return json_decode($response, true);
	}

	/**
	 * pendingTasks
	 *
	 * @param string $deviceId
	 * @return array|bool
	 */
	public static function pendingTasks($deviceId)
	{
		$query = ["device" => $deviceId]; 

		$response = self::curl('/tasks', $query);
		return json_decode($response, true);
	}

	/**
	 * getParameters
	 *
	 * @param array|string $parameters
	 * @param string $deviceId
	 * @return array|bool
	 */
	public static function getParameters($parameters, $deviceId)
	{
		$parameters = is_array($parameters) ? $parameters : [$parameters];
		$query = [
			"query" => json_encode(["_id" => $deviceId]),
			'projection' => implode(',', $parameters)
		];

		foreach ($parameters as $key => $val) {
			$parameters[$key] = explode('.', $val);
		}

		$response = self::curl('/devices', '', '', 'GET', '', $query);
		$response = json_decode($response, true);
		
		$returnValues = [];
		foreach ($parameters as $parameter) {
			$keyname = '';
			$val = $response[0];
			foreach ($parameter as $key) {
				$keyname .= '.'.$key;
				$val = $val[$key];
			}
			$val = $val['_value'];
			$keyname = trim($keyname, '.');
			$returnValues[$keyname] = $val;
		}
		
		return $returnValues;
	}

	public static function getTasks($deviceId)
	{
		$query = [ 'device' => $deviceId ];
		$response = self::curl('/tasks', $query);

		return json_decode($response, true);
	}

	public static function dispatchAction($op, $deviceId)
	{
		switch ($op) {
			case 'reset': return ACSRequest::factoryReset($deviceId); break;
			case 'reboot': return ACSRequest::reboot($deviceId); break;
			case 'delete': return ACSRequest::deleteDevice($deviceId); break;
			case 'refresh': return ACSRequest::refreshAllObjects($deviceId); break;
		}
	}
	

	public static function deleteTask($deviceId, $taskId)
	{
		$response = self::curl('/tasks/'.$taskId, '', '', 'DELETE');
		return json_encode($response, true);
	}

	public static function getTask($taskId)
	{
		$query = ['_id' => $taskId];
		$response = self::curl('/tasks', $query);
		return end(json_decode($response, true));
	}
}
