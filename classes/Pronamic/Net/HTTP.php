<?php

/**
 * Title: HTTP
 * Description: 
 * Copyright: Copyright (c) 2005 - 2010
 * Company: Pronamic
 * @author Remco Tolsma
 * @doc http://www.w3.org/Protocols/rfc2616/rfc2616.html
 * @version 1.0
 */
class Pronamic_Net_HTTP {
	/**
	 * US-ASCII CR, carriage return (13)
	 *
	 * @var char
	 */
	const CR = "\r";

	/**
	 * US-ASCII LF, linefeed (10)
	 *
	 * @var char
	 */
	const LF = "\n";

	/**
	 * Carriage return and linefeed
	 *
	 * @var string
	 */
	const CRLF = "\r\n";

	/**
	 * US-ASCII SP, space (32)
	 *
	 * @var char
	 */
	const SP = ' ';

	/**
	 * US-ASCII HT, horizontal-tab (9)
	 *
	 * @var char
	 */
	const HT = "\t";

	//////////////////////////////////////////////////	

	/**
	 * HTTP version number 1.0 indicator
	 *
	 * @var string
	 */
	const VERSION_1_0 = '1.0';

	/**
	 * HTTP version number 1.1 indicator
	 *
	 * @var string
	 */
	const VERSION_1_1 = '1.1';

	//////////////////////////////////////////////////

	/**
	 * Matches any US-ASCII control character 
	 * (octets 0 - 31) and DEL (127)>
	 * 
	 * @doc http://www.w3.org/Protocols/rfc2616/rfc2616-sec2.html#sec2.2
	 * @var string
	 */
	const PREG_CTL = '\000-\031\127';

	/**
	 * Separators
	 * "(" | ")" | "<" | ">" | "@"
	 * "," | ";" | ":" | "\" | <">
	 * "/" | "[" | "]" | "?" | "="
	 * "{" | "}" | SP | HT
	 * 
	 * @doc http://www.w3.org/Protocols/rfc2616/rfc2616-sec2.html#sec2.2
	 * @var string
	 */
	const PREG_SEPARATORS = '\(\)\<\>@,;\:\\"/\[\]\?\=\{\}\s\t';

	//////////////////////////////////////////////////

	/**
	 * Request the specified URL
	 * 
	 * @param orbis_net_Url $url
	 * @return orbis_net_HttpRequest
	 */
	public static function request($url, $method = HttpRequest::METHOD_GET, $data = null) {
		if(!$url instanceof Url) {
			$url = new Url($url);
		}

		$httpClient = new HttpClient();

		$request = $httpClient->getRequest($url);
		$request->setMethod($method);

		if(isset($data)) {
			if(is_array($data)) {
				$data = http_build_query($data, null, '&');
			}

			$request->setBody($data);
		}

		return $httpClient->request($url, $request);
	}

	//////////////////////////////////////////////////	

	/**
	 * Execute an GET request
	 * 
	 * @return orbis_net_HttpResponse
	 */
	public static function get($url) {
		return self::request($url, HttpRequest::METHOD_GET);
	}

	//////////////////////////////////////////////////	

	/**
	 * Execute an POST request
	 * 
	 * @return orbis_net_HttpResponse
	 */
	public static function post($url, $data = null) {
		return self::request($url, HttpRequest::METHOD_POST, $data);
	}

	//////////////////////////////////////////////////	

	/**
	 * Execute an POST request
	 * 
	 * @return orbis_net_HttpResponse
	 */
	public static function head($url) {
		return self::request($url, HttpRequest::METHOD_HEAD);
	}
}
