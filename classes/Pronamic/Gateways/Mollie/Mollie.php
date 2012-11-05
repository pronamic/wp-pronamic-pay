<?php
/**
 * Kant en klare class om met Mollie iDEAL aan de slag te gaan.
*
* @link https://www.mollie.nl/support/documentatie/betaaldiensten/ideal/
*/
class Pronamic_Gateways_Mollie_Mollie
{
	/**
	 * Minimum bedrag in cent.
	 */
	const MIN_TRANS_AMOUNT = 120;

	protected $partner_id;
	protected $profile_key;

	protected $testmode = false;

	protected $bank_id;

	/**
	 * Bank status
	 */
	protected $status;

	/**
	 * Het bedrag in centen.
	 *
	 * @var int
	 */
	protected $amount = 0;
	protected $description;
	protected $return_url;
	protected $report_url;

	protected $bank_url;
	protected $payment_url;

	protected $transaction_id;
	protected $paid_status     = false;
	protected $consumer_info   = array();

	protected $error_message   = '';
	protected $error_code      = 0;

	protected $api_host        = 'https://secure.mollie.nl';
	protected $api_port        = 443;

	public function __construct ($partner_id)
	{
		$this->setPartnerId($partner_id);
	}

	/**
	 * Haal de lijst van beschikbare banken
	 *
	 * @return array
	 */
	public function getBanks()
	{
		$query_variables = array (
				'a'          => 'banklist',
				'partner_id' => $this->partner_id,
		);

		if ($this->testmode) {
			$query_variables['testmode'] = 'true';
		}

		$banks_xml = $this->_sendRequest (
				'/xml/ideal/',
				http_build_query($query_variables, '', '&')
		);

		if (empty($banks_xml)) {
			return false;
		}

		$banks_object = $this->_XMLtoObject($banks_xml);

		if (!$banks_object or $this->_XMlisError($banks_object)) {
			return false;
		}

		$banks_array = array();

		foreach ($banks_object->bank as $bank) {
			$banks_array["{$bank->bank_id}"] = "{$bank->bank_name}";
		}

		return $banks_array;
	}

	/**
	 * Zet een betaling klaar bij de bank en maak de betalings URL beschikbaar
	 *
	 * @param $bank_id
	 * @param $amount
	 * @param $description
	 * @param $return_url
	 * @param $report_url
	 * @return bool
	 */
	public function createPayment ($bank_id, $amount, $description, $return_url, $report_url)
	{
		if (!$this->setBankId($bank_id))
		{
			$this->error_message = "De opgegeven bank \"$bank_id\" is onjuist of incompleet";
			return false;
		}

		if (!$this->setDescription($description))
		{
			$this->error_message = "De opgegeven omschrijving \"$description\" is incompleet";
			return false;
		}

		if (!$this->setAmount($amount))
		{
			$this->error_message = "Het opgegeven bedrag \"$amount\" is onjuist of te laag";
			return false;
		}

		if (!$this->setReturnURL($return_url))
		{
			$this->error_message = "De opgegeven return URL \"$return_url\" is onjuist";
			return false;
		}

		if (!$this->setReportURL($report_url))
		{
			$this->error_message = "De opgegeven report URL \"$report_url\" is onjuist";
			return false;
		}

		$query_variables = array (
				'a'           => 'fetch',
				'partnerid'   => $this->getPartnerId(),
				'bank_id'     => $this->getBankId(),
				'amount'      => $this->getAmount(),
				'description' => $this->getDescription(),
				'reporturl'   => $this->getReportURL(),
				'returnurl'   => $this->getReturnURL(),
		);

		if ($this->getProfileKey())
		{
			$query_variables['profile_key'] = $this->getProfileKey();
		}

		$create_xml = $this->_sendRequest(
				'/xml/ideal/',
				http_build_query($query_variables, '', '&')
		);

		$create_object = $this->_XMLtoObject($create_xml);

		if (!$create_object or $this->_XMLisError($create_object)) {
			return false;
		}

		$this->transaction_id = (string) $create_object->order->transaction_id;
		$this->bank_url       = (string) $create_object->order->URL;

		return true;
	}

	/**
	 * Kijk of er daadwerkelijk betaald is.
	 *
	 * @param $transaction_id
	 * @return bool
	 */
	public function checkPayment ($transaction_id)
	{
		if (!$this->setTransactionId($transaction_id)) {
			$this->error_message = "Er is een onjuist transactie ID opgegeven";
			return false;
		}

		$query_variables = array (
				'a'              => 'check',
				'partnerid'      => $this->getPartnerId(),
				'transaction_id' => $this->getTransactionId(),
		);

		if ($this->testmode) {
			$query_variables['testmode'] = 'true';
		}

		$check_xml = $this->_sendRequest(
				'/xml/ideal/',
				http_build_query($query_variables, '', '&')
		);

		$check_object = $this->_XMLtoObject($check_xml);

		if (!$check_object or $this->_XMLisError($check_object)) {
			return false;
		}

		$this->paid_status   = (bool) ($check_object->order->payed == 'true');
		$this->status        = (string) $check_object->order->status;
		$this->amount        = (int) $check_object->order->amount;
		$this->consumer_info = (isset($check_object->order->consumer)) ? (array) $check_object->order->consumer : array();

		return true;
	}

	public function CreatePaymentLink ($description, $amount)
	{
		if (!$this->setDescription($description) or !$this->setAmount($amount))
		{
			$this->error_message = "U moet een omschrijving én bedrag (in centen) opgeven voor de iDEAL link. Tevens moet het bedrag minstens " . self::MIN_TRANS_AMOUNT . ' eurocent zijn. U gaf ' . (int) $amount . ' cent op.';
			return false;
		}

		$query_variables = array (
				'a'           => 'create-link',
				'partnerid'   => $this->partner_id,
				'amount'      => $this->getAmount(),
				'description' => $this->getDescription(),
		);

		$create_xml = $this->_sendRequest(
				'/xml/ideal/',
				http_build_query($query_variables, '', '&')
		);

		$create_object = $this->_XMLtoObject($create_xml);

		if (!$create_object or $this->_XMLisError($create_object)) {
			return false;
		}

		$this->payment_url = (string) $create_object->link->URL;
		return true;
	}

	/**
	 * Verstuur een HTTP verzoek naar de Mollie API.
	 *
	 * @param $path string
	 * @param $data string
	 * @return bool|string
	 */
	protected function _sendRequest ($path, $data = '')
	{
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "{$this->api_host}{$path}?{$data}");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_ENCODING, ""); // Tell server which Encodings (gzip, deflate) we support.

		$body = curl_exec($ch);

		if (curl_errno($ch) == CURLE_SSL_CACERT)
		{
			/*
			 * On some servers, the list of installed certificates is outdated or not present at all (the ca-bundle.crt
			 		* is not installed). So we tell cURL which certificates we trust. Then we retry the requests.
			*/
			curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . DIRECTORY_SEPARATOR . "cacert.pem");
			$body = curl_exec($ch);
		}

		if (strpos(curl_error($ch), "certificate subject name 'mollie.nl' does not match target host") !== FALSE)
		{
			/*
			 * On some servers, the wildcard SSL certificate is not processed correctly. This happens with OpenSSL 0.9.7
			* from 2003.
			*/
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
			$body = curl_exec($ch);
		}

		var_dump(curl_error($ch));

		curl_close($ch);

		return $body;
	}

	protected function _XMLtoObject ($xml)
	{
		$xml_object = @simplexml_load_string($xml);
		if (!$xml_object)
		{
			$this->error_code = -2;
			$this->error_message = "Kon XML resultaat niet verwerken";
			return false;
		}

		return $xml_object;
	}

	/**
	 * Geeft het XML antwoord wat we van Mollie hebben ontvangen een error aan?
	 *
	 * @param $xml SimpleXMLElement
	 * @return bool
	 */
	protected function _XMLisError(SimpleXMLElement $xml)
	{
		/*
		 * Normale API errors, zoals ongeldige parameters et cetera.
		*/
		if (isset($xml->item))
		{
			$attributes = $xml->item->attributes();
			if ($attributes['type'] == 'error')
			{
				$this->error_message = (string) $xml->item->message;
				$this->error_code    = (string) $xml->item->errorcode;

				return true;
			}
		}

		/*
		 * iDEAL bank fouten.
		*/
		if (isset($xml->order->error) && (string) $xml->order->error == "true") {
			$this->error_message = $xml->order->message;
			$this->error_code = -1;
			return true;
		}

		return false;
	}

	/* Getters en setters */
	public function setProfileKey($profile_key)
	{
		if (is_null($profile_key))
			return false;
			
		return ($this->profile_key = $profile_key);
	}

	public function getProfileKey()
	{
		return $this->profile_key;
	}

	public function setPartnerId ($partner_id)
	{
		if (!is_numeric($partner_id)) {
			return false;
		}

		return ($this->partner_id = $partner_id);
	}

	public function getPartnerId ()
	{
		return $this->partner_id;
	}

	public function setTestmode ($enable = true)
	{
		return ($this->testmode = $enable);
	}

	public function setBankId ($bank_id)
	{
		if (!is_numeric($bank_id))
			return false;

		return ($this->bank_id = $bank_id);
	}

	public function getBankId ()
	{
		return $this->bank_id;
	}

	/**
	 * @param $amount
	 * @return bool|float
	 */
	public function setAmount ($amount)
	{
		if (is_string($amount) && !ctype_digit($amount)) {
			return false;
		}

		if (is_float($amount)) {
			$amount = round($amount);
		}

		if (self::MIN_TRANS_AMOUNT > $amount) {
			return false;
		}

		return ($this->amount = $amount);
	}

	/**
	 * @return int
	 */
	public function getAmount ()
	{
		return $this->amount;
	}

	public function setDescription ($description)
	{
		$description = substr($description, 0, 29);

		return ($this->description = $description);
	}

	public function getDescription ()
	{
		return $this->description;
	}

	public function setReturnURL ($return_url)
	{
		return ($this->return_url = filter_var($return_url, FILTER_VALIDATE_URL));
	}

	public function getReturnURL ()
	{
		return $this->return_url;
	}

	public function setReportURL ($report_url)
	{
		return ($this->report_url = filter_var($report_url, FILTER_VALIDATE_URL));
	}

	public function getReportURL ()
	{
		return $this->report_url;
	}

	public function setTransactionId ($transaction_id)
	{
		if (empty($transaction_id))
			return false;

		return ($this->transaction_id = $transaction_id);
	}

	public function getTransactionId ()
	{
		return $this->transaction_id;
	}

	public function getBankURL ()
	{
		return $this->bank_url;
	}

	public function getPaymentURL ()
	{
		return (string) $this->payment_url;
	}

	public function getPaidStatus ()
	{
		return $this->paid_status;
	}

	public function getBankStatus()
	{
		return $this->status;
	}

	public function getConsumerInfo ()
	{
		return $this->consumer_info;
	}

	public function getErrorMessage ()
	{
		return $this->error_message;
	}

	public function getErrorCode ()
	{
		return $this->error_code;
	}
}
