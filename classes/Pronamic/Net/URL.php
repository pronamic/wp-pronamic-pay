<?php

/**
 * Title: URL
 * Description:
 * Copyright: Copyright (c) 2005 - 2010
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_Net_URL {
	/**
	 * The path seperator within an URL
 	 *
 	 * @var string
	 */
	const PATH_SEPERATOR = '/';

	///////////////////////////////////////////////////////////////////////////
	
	/**
	 * Indicator for FTP scheme
	 * 
	 * @var string
	 */
	const SCHEME_FTP = 'ftp';

	/**
	 * Indicator for FTPS scheme
	 * 
	 * @var string
	 */
	const SCHEME_FTPS = 'ftps';

	/**
	 * Indicator for HTTP scheme
	 * 
	 * @var string
	 */
	const SCHEME_HTTP = 'http';

	/**
	 * Indicator for HTTPS scheme
	 * 
	 * @var string
	 */
	const SCHEME_HTTPS = 'https';

	/**
	 * Indicator for GOPHER scheme
	 * 
	 * @var string
	 */
	const SCHEME_GOPHER = 'gopher';

	/**
	 * Indicator for MAILTO scheme
	 * 
	 * @var string
	 */
	const SCHEME_MAILTO = 'mailto';

	/**
	 * Indicator for NEWS scheme
	 * 
	 * @var string
	 */
	const SCHEME_NEWS = 'news';

	/**
	 * Indicator for NNTP scheme
	 * 
	 * @var string
	 */
	const SCHEME_NNTP = 'nntp';

	/**
	 * Indicator for TELNET scheme
	 * 
	 * @var string
	 */
	const SCHEME_TELNET = 'telnet';

	/**
	 * Indicator for WAIS scheme
	 * 
	 * @var string
	 */
	const SCHEME_WAIS = 'wais';

	/**
	 * Indicator for FILE scheme
	 * 
	 * @var string
	 */
	const SCHEME_FILE = 'file';

	/**
	 * Indicator for PROSPERO scheme
	 * 
	 * @var string
	 */
	const SCHEME_PROSPERO = 'prospero';

	///////////////////////////////////////////////////////////////////////////

	/**
	 * The default ports of the specified schemes
	 * 
	 * @var array
	 */
	public static $defaultPorts = array(
		self::SCHEME_FTP => 21 , 
		self::SCHEME_FTPS => 990 , 
		self::SCHEME_HTTP => 80 , 
		self::SCHEME_HTTPS => 443 , 
		self::SCHEME_GOPHER => 70 , 
		self::SCHEME_MAILTO => null ,
		self::SCHEME_NEWS => 119 , 
		self::SCHEME_NNTP => 119 , 
		self::SCHEME_TELNET => 23 , 
		self::SCHEME_WAIS => 210 , 
		self::SCHEME_FILE => null , 
		self::SCHEME_PROSPERO => 191
	);

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Scheme
	 * 
	 * @var string
	 */
	private $scheme;

	/**
	 * Host
	 * 
	 * @var string
	 */
	private $host;

	/**
	 * Port
	 * 
	 * @var int
	 */
	private $port;

	/**
	 * User
	 * 
	 * @var string
	 */
	private $user;

	/**
	 * Pass
	 * 
	 * @var string
	 */
	private $pass;

	/**
	 * Path 
	 * 
	 * @var string
	 */
	private $path;

	/**
	 * Query
	 * 
	 * @var string
	 */
	private $query;

	/**
	 * Fragment
	 * 
	 * @var string
	 */
	private $fragment;

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Constructs and initializes an URL 
	 *
	 * @param 
	 */
	public function __construct($url = null) {
		if($url != null) {
			self::parse($url, $this);
		}
	}

	///////////////////////////////////////////////////////////////////////////

	public function getScheme() {
		return $this->scheme;
	}
	
	public function setScheme($scheme) {
		$this->scheme = $scheme;
	}

	///////////////////////////////////////////////////////////////////////////

	public function getHost() {
		return $this->host;
	}
	
	public function setHost($host) {
		$this->host = $host;
	}

	///////////////////////////////////////////////////////////////////////////

	public function getPort($default = false) {
		if($this->port == null && $default) {
			return $this->getDefaultPort($this->scheme);
		} else {
			return $this->port;
		}
	}
	
	public function setPort($port) {
		$this->port = $port;
	}

	///////////////////////////////////////////////////////////////////////////

	public function getUser() {
		return $this->user;
	}
	
	public function setUser($user) {
		$this->user = $user;
	}

	///////////////////////////////////////////////////////////////////////////

	public function getPass() {
		return $this->pass;
	}
	
	public function setPass($pass) {
		$this->pass = $pass;
	}

	///////////////////////////////////////////////////////////////////////////

	public function getPath($default = false) {
		$prefix = isset($this->path[0]) && $this->path[0] === self::PATH_SEPERATOR;

		return (!$prefix && $default ? self::PATH_SEPERATOR : '') . $this->path; 
	}

	public function setPath($path) {
		$this->path = $path;
	}

	///////////////////////////////////////////////////////////////////////////

	public function getQuery() {
		return $this->query;
	}
	
	public function setQuery($query) {
		$this->query = $query;
	}

	///////////////////////////////////////////////////////////////////////////

	public function getFragment() {
		return $this->fragment;
	}
	
	public function setFragment($fragment) {
		$this->fragment = $fragment;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Equals
	 * 
	 * @param $var the variabele to compare this object with
	 * @return boolean true if equal, false otherwise
	 */
	public function equals($var) {
		if($var instanceof self) {
			return self::compare($this, $var, false, true);
		} else {
			return false;
		}
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Compare the specified URLs
	 * 
	 * @param self $url1
	 * @param self $url2
	 * @param boolean $sub
	 * @param boolean $query
	 */
	public static function compare(self $url1, self $url2, $sub = true, $query = false) {
		// Scheme
		$scheme1 = $url1->getScheme();
		$scheme2 = $url2->getScheme();

		if($scheme1 != null && $scheme2 != null) {
			if(strcasecmp($scheme1, $scheme2) !== 0) {
				return false;
			}
		}
	
		// Host
		$host1 = $url1->getHost();
		$host2 = $url2->getHost();

		if($host1 != null && $host2 != null) {
			if(strcasecmp($host1, $host2) !== 0) {
				return false;
			}
		}

		// Port
		$port1 = $url1->getPort(true);
		$port2 = $url2->getPort(true);

		if($port1 != null && $port2 != null) {
			if($port1 != $port2) {
				return false;
			}
		}

		// Path
		$path1 = $url1->getPath(true);
		$path2 = $url2->getPath(true);

		if($path1 != null && $path2 != null) {
			if($sub) {
				if(strncmp($path1, $path2, strlen($path2)) !== 0) {
					return false;
				}
			} else {
				if(strcmp($path1, $path2) !== 0) {
					return false;
				}
			}
		}

		// Query
		$query1 = $url1->getQuery();
		$query2 = $url2->getQuery();

		if($query) {
			if(strcasecmp($query1, $query2) !== 0) {
				return false;
			}
		}

		return true;
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Parse the specified string to an URL object
	 * 
	 * @param $url
	 * @return orbis_net_Url
	 */
	public static function parse($string, self $url = null) {
		$data = @parse_url($string);

		if($data === false) {
			// throw exception

			return null;
		} else {
			if($url == null) {
				$url = new self();
			}

			if(isset($data['scheme'])) {
				$url->setScheme($data['scheme']);
			}

			if(isset($data['host'])) {
				$url->setHost($data['host']);
			}

			if(isset($data['port'])) {
				$url->setPort($data['port']);
			}

			if(isset($data['user'])) {
				$url->setUser($data['user']);
			}

			if(isset($data['pass'])) {
				$url->setPass($data['pass']);
			}

			if(isset($data['path'])) {
				$url->setPath($data['path']);
			}

			if(isset($data['query'])) {
				$url->setQuery($data['query']);
			} else {
				$url->setQuery(null);
			}

			if(isset($data['fragment'])) {
				$url->setFragment($data['fragment']);
			} else {
				$url->setFragment(null);
			}

			return $url;
		}
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Build a query part of the specified data
	 * 
	 * @param array $data
	 * @return string
	 */
	public static function query(array $data, $numericPrefix = '', $argSeparator = '&') {
		return http_build_query($data, $numericPrefix, $argSeparator);
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get a relative URL 
	 * 
	 * @param orbis_net_Url $contextUrl
	 * @param orbis_net_Url $referenceUrl
	 * @return unknown_type
	 */
	public static function relative(self $contextUrl, self $referenceUrl) {
 		if($contextUrl->getScheme() != $referenceUrl->getScheme() || $contextUrl->getHost() != $referenceUrl->getHost()) {
			return $referenceUrl;
		}

		$contextPath = $contextUrl->getPath(true);
		$referencePath = $referenceUrl->getPath(true);
    
		$offset = 0;
		while(($pos = strpos($contextPath, self::DS, $offset)) !== false) {
			$pos++;

			if(substr($contextPath, 0, $pos) == substr($referencePath, 0, $pos)) {
				$sharedLength = $pos;
				$offset = $pos;
			} else {
				break;
			}
		}

		$path = substr($contextPath, $sharedLength);
		$dirsUp = substr_count($path, self::DS);
		$dirPart = ($dirsUp > 0) ? str_repeat('..' . self::DS, $dirsUp) : '';

		$path = substr($referencePath, $sharedLength);

		return $dirPart . $path;
	} 

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Get the default port for the specified scheme
	 * 
	 * @param $scheme an scheme
	 * @return string
	 */
	public static function getDefaultPort($scheme) {
		if(isset(self::$defaultPorts[$scheme])) {
			return self::$defaultPorts[$scheme];
		} else {
			return null;
		}
	}

	///////////////////////////////////////////////////////////////////////////

	/**
	 * Create string representation of this Url
	 * 
	 * @return string
	 */
	public function __toString() {
		$str = '';

		$str .= $this->scheme;
		$str .= '://';

		if(!empty($this->user)) {
			$url .= $this->user;
			
			if(!empty($this->password)) {
				$url .= ':' . $this->password;
			}
			
			$url .= '@';
		} 

		$str .= $this->host;

		if(isset($this->port)) {
			$str .= ':' . $this->port;
		}

		$str .= $this->getPath(true);

		if(isset($this->query)) {
			$str .= '?' . $this->query;
		}

		if(isset($this->fragment)) {
			$str .= '#' . $this->fragment;
		}

		return $str;
	}
}
