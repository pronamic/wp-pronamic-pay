<?php

/**
 * Title: Payments repository
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_WordPress_IDeal_PaymentsRepository {
	/**
	 * Update table
	 */
	public static function updateTable() {
        require_once ABSPATH . '/wp-admin/includes/upgrade.php';

		global $wpdb;

		$charsetCollate = '';
        if(!empty($wpdb->charset)) {
            $charsetCollate = 'DEFAULT CHARACTER SET ' . $wpdb->charset;
        }
        if(!empty($wpdb->collate)) {
            $charsetCollate .= ' COLLATE ' . $wpdb->collate;
        }

        // iDEAL payments table
        $tableName = self::getPaymentsTableName();

        $sql = "CREATE TABLE $tableName (
			id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT , 
			configuration_id MEDIUMINT(8) UNSIGNED NOT NULL ,    
			transaction_id VARCHAR(16) NULL , 
  			date_gmt DATETIME NOT NULL , 
			amount DECIMAL(10, 2) NOT NULL , 
			currency VARCHAR(8) NOT NULL , 
			expiration_period VARCHAR(8) NOT NULL , 
			language VARCHAR(8) NOT NULL ,
			entrance_code VARCHAR(32) NOT NULL ,
			description TEXT NOT NULL , 
			consumer_name VARCHAR(35) NULL ,
			consumer_account_number VARCHAR(10) NULL ,  
			consumer_city VARCHAR(24) NULL ,
  			status VARCHAR(32) NULL DEFAULT NULL , 
  			status_requests MEDIUMINT(8) DEFAULT 0 ,
  			source VARCHAR(32) NULL DEFAULT NULL , 
  			source_id VARCHAR(32) NULL DEFAULT NULL ,  
			PRIMARY KEY (id) , 
			KEY configuration_id (configuration_id) , 
			UNIQUE (entrance_code)
			) $charsetCollate;";

        dbDelta($sql);
    }

	//////////////////////////////////////////////////
    
    /**
     * Drop the tables
     */
    public static function dropTables() {
		global $wpdb;

		$wpdb->query("DROP TABLE IF EXISTS " . self::getPaymentsTableName());
    }

	//////////////////////////////////////////////////

    /**
     * Get the iDEAL payments table name
     * 
     * @return string
     */
    public static function getPaymentsTableName() {
		global $wpdb;

		return $wpdb->prefix . 'pronamic_ideal_payments';
    }

	//////////////////////////////////////////////////

    /**
     * Get transaction from the specified result object
     * 
     * @param stdClass $result
     * @return Transaction
     */
	private function getTransactionFromResult($result) {
		$transaction = new Pronamic_IDeal_Transaction();

		$transaction->setId($result->transactionId);
		$transaction->setDescription($result->description);
		$transaction->setAmount($result->amount);
		$transaction->setCurrency($result->currency);
		$transaction->setLanguage($result->language);
		$transaction->setEntranceCode($result->entranceCode);
		$transaction->setExpirationPeriod($result->expirationPeriod);
		$transaction->setStatus($result->status);
		$transaction->setConsumerName($result->consumerName);
		$transaction->setConsumerAccountNumber($result->consumerAccountNumber);
		$transaction->setConsumerCity($result->consumerCity);

		return $transaction;
	}

	//////////////////////////////////////////////////
	
	private function getPaymentFromResult($result) {
		$payment = new Pronamic_WordPress_IDeal_Payment();

		$payment->transaction = self::getTransactionFromResult($result);
		$payment->setId($result->purchaseId);
		$payment->setDate(new DateTime($result->dateGmt, new DateTimeZone('UTC')));
		$payment->setSource($result->source, $result->sourceId);
		$payment->configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById($result->configurationId);

		return $payment;
	}
	
	private function getPaymentQuery($condition = '') {
		$table = self::getPaymentsTableName();

        $query = "
        	SELECT 
        		payment.id AS purchaseId ,
        		payment.configuration_id AS configurationId , 
        		payment.transaction_id AS transactionId ,
        		payment.date_gmt AS dateGmt ,
        		payment.description AS description , 
        		payment.amount AS amount , 
        		payment.currency AS currency , 
        		payment.language AS language , 
        		payment.entrance_code AS entranceCode , 
        		payment.expiration_period AS expirationPeriod , 
        		payment.consumer_name AS consumerName ,
        		payment.consumer_account_number AS consumerAccountNumber , 
        		payment.consumer_city AS consumerCity ,  
        		payment.status AS status , 
        		payment.status_requests AS statusRequests ,  
        		payment.source AS source , 
        		payment.source_id AS sourceId  
			FROM 
	        	$table AS payment
	        $condition
	        ORDER BY
	        	payment.date_gmt DESC
        ";

		return $query;
	}

    /**
     * Get the payments
     * 
     * @return array
     */
    public static function getPayments() {
        global $wpdb;

		$payments = array();
        $results = $wpdb->get_results(self::getPaymentQuery(), OBJECT_K);

        foreach($results as $result) {
        	$payments[] = self::getPaymentFromResult($result); 
        }

        return $payments;
    }

	//////////////////////////////////////////////////

    public static function getPaymentByQuery($query) {
		global $wpdb;

        $payment = null;

        $result = $wpdb->get_row($query, OBJECT);

        if($result) {
        	$payment = self::getPaymentFromResult($result); 
        }

        return $payment;
    }

    /**
     * Get iDEAL configuration by the specified ID
     * 
     * @param string $id
     */
    public static function getPaymentByIdAndEc($transactionId, $entranceCode) {
		global $wpdb;

        return self::getPaymentByQuery($wpdb->prepare(self::getPaymentQuery("
			WHERE 
				payment.transaction_id = %s 
					AND
				payment.entrance_code = %s
        	") , $transactionId , $entranceCode
        ));
    }

    public static function getPaymentById($id) {
		global $wpdb;

        return self::getPaymentByQuery($wpdb->prepare(self::getPaymentQuery("
			WHERE 
				payment.id = %d
        	") , $id 
        ));
    }
    
    public static function updateStatus($payment) {
		global $wpdb;

		$transaction = $payment->transaction;

		$result = $wpdb->update(
			self::getPaymentsTableName() , 
			array( 
				'status' => $transaction->getStatus() ,
				'consumer_name' => $transaction->getConsumerName() , 
				'consumer_account_number' => $transaction->getConsumerAccountNumber() , 
				'consumer_city' => $transaction->getConsumerCity() 
			) , 
			array(
				'id' => $payment->getId()
			) , 
			array('%s', '%s', '%s', '%s') , 
			array('%d')
		);

        return $result;
    }

	public static function updatePayment($payment) {
		global $wpdb;

		$table = self::getPaymentsTableName();

		$configuration = $payment->configuration;
		$transaction = $payment->transaction;

		$data = array( 
			'configuration_id' => $configuration->getId() ,
			'transaction_id' => $transaction->getId() , 
			'amount' => $transaction->getAmount() , 
			'currency' => $transaction->getCurrency() ,
			'expiration_period' => $transaction->getExpirationPeriod() ,
			'language' => $transaction->getLanguage() ,
			'entrance_code' => $transaction->getEntranceCode() ,
			'description' => $transaction->getDescription() ,
			'status' => $transaction->getStatus() ,
			'consumer_name' => $transaction->getConsumerName() ,
			'consumer_account_number' => $transaction->getConsumerAccountNumber() ,
			'consumer_city' => $transaction->getConsumerCity() ,
			'date_gmt' => $payment->getDate()->format('Y-m-d H:i:s') , 
			'source' => $payment->getSource() , 
			'source_id' => $payment->getSourceId() 
		);

		$format = array('%d', '%s', '%F', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');

		// Insert
        if(empty($payment->id)) {
            // Insert
            $result = $wpdb->insert($table, $data, $format);

            if($result !== false) {
            	$payment->setId($wpdb->insert_id);
            }
        } else {
            // Update
			$result = $wpdb->update($table, $data, array('id' => $payment->getId()), $format, array('%d'));
        }

        return $result;
	}

	//////////////////////////////////////////////////

	public static function getNumberPayments() {
		global $wpdb;

		$table = self::getPaymentsTableName();

		$numberPayments = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table;"));
		
		return $numberPayments;
	}
}
