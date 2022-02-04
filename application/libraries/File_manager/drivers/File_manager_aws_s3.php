<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @throws Exception
 */
function invoke_aws_api($ak_id, $ak_sec, $region, $verb, $host, $addr, $headers=array(), $content="")
{
	$aws_access_key_id = $ak_id;
	$aws_secret_access_key = $ak_sec;

	$aws_service_name = 's3';

	$timestamp = gmdate('Ymd\THis\Z');
	$date = gmdate('Ymd');

	$request_headers = $headers;
	$request_headers['Date'] = $timestamp;
	$request_headers['Host'] = $host;
	$request_headers['x-amz-content-sha256'] = hash('sha256', $content);
	ksort($request_headers);

	// Canonical headers
	$canonical_headers = [];
	foreach($request_headers as $key => $value) {
		$canonical_headers[] = strtolower($key) . ":" . $value;
	}
	$canonical_headers = implode("\n", $canonical_headers);

	// Signed headers
	$signed_headers = [];
	foreach($request_headers as $key => $value) {
		$signed_headers[] = strtolower($key);
	}
	$signed_headers = implode(";", $signed_headers);

	// Cannonical request
	$canonical_request = [];
	$canonical_request[] = $verb;
	$canonical_request[] = "/" . $addr;
	$canonical_request[] = "";
	$canonical_request[] = $canonical_headers;
	$canonical_request[] = "";
	$canonical_request[] = $signed_headers;
	$canonical_request[] = hash('sha256', $content);
	$canonical_request = implode("\n", $canonical_request);
	$hashed_canonical_request = hash('sha256', $canonical_request);

	// AWS Scope
	$scope = [];
	$scope[] = $date;
	$scope[] = $region;
	$scope[] = $aws_service_name;
	$scope[] = "aws4_request";

	// String to sign
	$string_to_sign = [];
	$string_to_sign[] = "AWS4-HMAC-SHA256";
	$string_to_sign[] = $timestamp;
	$string_to_sign[] = implode('/', $scope);
	$string_to_sign[] = $hashed_canonical_request;
	$string_to_sign = implode("\n", $string_to_sign);

	// Signing key
	$kSecret = 'AWS4' . $aws_secret_access_key;
	$kDate = hash_hmac('sha256', $date, $kSecret, true);
	$kRegion = hash_hmac('sha256', $region, $kDate, true);
	$kService = hash_hmac('sha256', $aws_service_name, $kRegion, true);
	$kSigning = hash_hmac('sha256', 'aws4_request', $kService, true);

	// Signature
	$signature = hash_hmac('sha256', $string_to_sign, $kSigning);

	// Authorization
	$authorization = [
		'Credential=' . $aws_access_key_id . '/' . implode('/', $scope),
		'SignedHeaders=' . $signed_headers,
		'Signature=' . $signature,
	];
	$authorization = 'AWS4-HMAC-SHA256' . ' ' . implode( ',', $authorization);

	// Curl headers
	$curl_headers = [ 'Authorization: ' . $authorization ];
	foreach($request_headers as $key => $value) {
		$curl_headers[] = $key . ": " . $value;
	}


	$url = 'https://' . $host . '/' . $addr;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $verb);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
	curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if($http_code >= 400)
		throw new Exception('Error : Failed to invoke');
}

class File_manager_aws_s3 extends CI_Driver
{
	/**
	 * @throws Exception
	 */
	public function upload_file_from_field($file_field, $filename, $cfg) : array
	{
		$headers = array(
			"x-amz-acl" => "public-read",
			"Content-Type" => $_FILES[$file_field]["type"]
		);
		$content = file_get_contents($_FILES[$file_field]["tmp_name"]);
		invoke_aws_api($cfg["access_key_id"], $cfg["access_key_secret"], $cfg["region"],
			"PUT", $cfg["hostname"], $filename, $headers, $content);

		return array(
			"filename" => $filename,
			"url" => $cfg["url_prefix"].$filename
		);
	}

	/**
	 * @throws Exception
	 */
	public function delete_file($filename, $cfg)
	{
		invoke_aws_api($cfg["access_key_id"], $cfg["access_key_secret"], $cfg["region"],
			"DELETE", $cfg["hostname"], $filename);
	}

	public function is_support_get_size($cfg) : bool
	{
		return false;
	}

	public function get_size($cfg) : int
	{
		return 0;
	}

	public function test_configure($cfg): bool
	{
		try {
			invoke_aws_api($cfg["access_key_id"], $cfg["access_key_secret"], $cfg["region"],
				"HEAD", $cfg["hostname"], "");
		} catch (Exception $e) {
			return false;
		}
		return true;
	}
}
