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
	public static function update_table() {
        require_once ABSPATH . '/wp-admin/includes/upgrade.php';

		global $wpdb;

		$charset_collate = '';
        if ( ! empty( $wpdb->charset ) ) {
            $charset_collate = 'DEFAULT CHARACTER SET ' . $wpdb->charset;
        }
        if ( ! empty( $wpdb->collate ) ) {
            $charset_collate .= ' COLLATE ' . $wpdb->collate;
        }

        // iDEAL payments table
        $tableName = self::getPaymentsTableName();

        $sql = "CREATE TABLE $tableName (
			id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			configuration_id MEDIUMINT(8) UNSIGNED NOT NULL,
			purchase_id VARCHAR(16) NULL,
			transaction_id VARCHAR(32) NULL,
  			date_gmt DATETIME NOT NULL,
			amount DECIMAL(10, 2) NOT NULL,
			currency VARCHAR(8) NOT NULL,
			expiration_period VARCHAR(8) NOT NULL,
			language VARCHAR(8) NOT NULL,
			entrance_code VARCHAR(40) NULL,
			description TEXT NOT NULL,
			consumer_name VARCHAR(35) NULL,
			consumer_account_number VARCHAR(10) NULL,
			consumer_iban VARCHAR(34) NULL,
			consumer_bic VARCHAR(11) NULL,
			consumer_city VARCHAR(24) NULL,
  			status VARCHAR(32) NULL DEFAULT NULL,
  			status_requests MEDIUMINT(8) DEFAULT 0,
  			source VARCHAR(32) NULL DEFAULT NULL,
  			source_id VARCHAR(32) NULL DEFAULT NULL,
			PRIMARY KEY  (id),
			KEY configuration_id (configuration_id),
			UNIQUE (entrance_code)
			) $charset_collate;";

        dbDelta( $sql );
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
	 * Get payment from result
	 * 
	 * @param mixed $result
	 */
	private function getPaymentFromResult($result) {
		$payment = new Pronamic_WordPress_IDeal_Payment();

		$payment->setId( $result->id );
		$payment->setDate( new DateTime( $result->date_gmt, new DateTimeZone( 'UTC' ) ) );
		$payment->setSource( $result->source, $result->source_id );
		$payment->configuration = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $result->configuration_id );

		$payment->transaction_id          = $result->transaction_id;
		$payment->purchase_id             = $result->purchase_id;
		$payment->description             = $result->description;
		$payment->amount                  = $result->amount;
		$payment->currency                = $result->currency;
		$payment->language                = $result->language;
		$payment->entrance_code           = $result->entrance_code;
		$payment->expiration_period       = $result->expiration_period;
		$payment->status                  = $result->status;
		$payment->consumer_name           = $result->consumer_name;
		$payment->consumer_account_number = $result->consumer_account_number;
		$payment->consumer_iban           = $result->consumer_iban;
		$payment->consumer_bic            = $result->consumer_bic;
		$payment->consumer_city           = $result->consumer_city;
		
		return $payment;
	}

	//////////////////////////////////////////////////

	/**
	 * Get payment query
	 * 
	 * @param array $query
	 */
	private function getPaymentQuery($query = array()) {
		global $wpdb;

		$table = self::getPaymentsTableName();

		$query = wp_parse_args( $query, array(
			'payment_id'     => null,
			'purchase_id'    => null,
			'transaction_id' => null,
			'entrance_code'  => null,
			'source'         => null,
			'source_id'      => null,
			's'              => null,
			'number'         => null,
			'offset'         => null,
			'orderby'        => 'date_gmt',
			'order'          => null
		) );

		// Where
		$where = ' WHERE 1 = 1';

		if ( isset( $query['payment_id'] ) ) {
			$where .= $wpdb->prepare( ' AND payment.id = %s', $query['payment_id'] );
		}
		
		if ( isset( $query['purchase_id'] ) ) {
			$where .= $wpdb->prepare(' AND payment.purchase_id = %s', $query['purchase_id'] );
		}
		
		if ( isset( $query['transaction_id'] ) ) {
			$where .= $wpdb->prepare(' AND payment.transaction_id = %s', $query['transaction_id'] );
		}

		if ( isset( $query['entrance_code'] ) ) {
			$where .= $wpdb->prepare( ' AND payment.entrance_code = %s', $query['entrance_code'] );
		}

		if ( isset( $query['source'] ) ) {
			$where .= $wpdb->prepare( ' AND payment.source = %s', $query['source'] );
		}

		if ( isset( $query['source_id'] ) ) {
			$where .= $wpdb->prepare( ' AND payment.source_id = %s', $query['source_id'] );
		}

		if ( isset( $query['s'] ) ) {
			$term = $query['s'];
			
			if ( ! empty( $term ) ) {
				$n = '%';
					
				$term = esc_sql( like_escape( $term ) );

				$conditions = array();

				$columns = array( 
					'purchase_id',
					'transaction_id',
					'amount',
					'consumer_name',
					'consumer_account_number',
					'consumer_iban',
					'consumer_bic',
					'consumer_city'
				);

				foreach ( $columns as $column ) {
					$conditions[] = "payment.{$column} LIKE '{$n}{$term}{$n}'";
				}
	
				$search  = ' AND (' . implode( ' OR ', $conditions ) . ')';
	
				$where .= $search;
			}
		}
		
		// Limit
		$limit = '';
		if ( isset( $query['number'] ) ) {
			if ( isset( $query['offset'] ) ) {
				$limit = $wpdb->prepare( "LIMIT %d, %d", $query['offset'], $query['number'] );
			} else {
				$limit = $wpdb->prepare( "LIMIT %d", $query['number'] );
			}
		}

		// Order by
		$query['order'] = strtoupper( $query['order'] );
		if ( $query['order'] == 'ASC' ) {
			$order = 'ASC';
		} else {
			$order = 'DESC';
		}

		$orderby = $query['orderby'];
		$orderby = "ORDER BY $orderby $order";

		// Query
        $query = "
        	SELECT 
        		payment.id,
        		payment.configuration_id,
        		payment.purchase_id,
        		payment.transaction_id,
        		payment.date_gmt,
        		payment.description,
        		payment.amount,
        		payment.currency,
        		payment.language,
        		payment.entrance_code,
        		payment.expiration_period,
        		payment.consumer_name,
        		payment.consumer_account_number,
        		payment.consumer_iban,
        		payment.consumer_bic,
        		payment.consumer_city,
        		payment.status,
        		payment.status_requests,
        		payment.source,
        		payment.source_id
			FROM 
	        	$table AS payment
	        $where 
	        $orderby
	        $limit
        ";

		return $query;
	}

    /**
     * Get payments by query
     * 
     * @return array
     */
    public static function getPayments( $query = array() ) {
        global $wpdb;

		$payments = array();
        $results = $wpdb->get_results( self::getPaymentQuery( $query ), OBJECT_K );

        foreach ( $results as $result ) {
        	$payments[] = self::getPaymentFromResult( $result ); 
        }

        return $payments;
    }

	//////////////////////////////////////////////////

    /**
     * Get payment by query
     * 
     * @return Pronamic_WordPress_IDeal_Payment 
     */
    public static function getPaymentByQuery( $query ) {
		global $wpdb;

        $payment = null;

        $result = $wpdb->get_row( $query, OBJECT );

        if ( $result ) {
        	$payment = self::getPaymentFromResult( $result ); 
        }

        return $payment;
    }

    /**
     * Get payment by transaction id and entrance code
     * 
     * @return Pronamic_WordPress_IDeal_Payment 
     */
    public static function getPaymentByIdAndEc( $transactionId, $entranceCode = null ) {
        return self::getPaymentByQuery( self::getPaymentQuery( array(
			'transaction_id' => $transactionId,
        	'entrance_code'  => $entranceCode
        ) ) );
    }

    /**
     * Get payment by id
     * 
     * @return Pronamic_WordPress_IDeal_Payment 
     */
    public static function getPaymentById( $id ) {
        return self::getPaymentByQuery( self::getPaymentQuery( array(
        	'payment_id' => $id
        ) ) );
    }

    /**
     * Get payment by source
     * 
     * @return Pronamic_WordPress_IDeal_Payment 
     */
    public static function getPaymentBySource( $source, $id = null ) {
        return self::getPaymentByQuery( self::getPaymentQuery( array(
        	'source'    => $source,
        	'source_id' => $id
        ) ) );
    }

	//////////////////////////////////////////////////

    /**
     * Update status
     * 
     * @param unknown_type $payment
     */
    public static function updateStatus( Pronamic_WordPress_IDeal_Payment $payment ) {
		global $wpdb;

		$result = $wpdb->update(
			self::getPaymentsTableName(),
			array( 
				'status'                  => $payment->status,
				'consumer_name'           => $payment->consumer_name,
				'consumer_account_number' => $payment->consumer_account_number,
				'consumer_iban'           => $payment->consumer_iban,
				'consumer_bic'            => $payment->consumer_bic,
				'consumer_city'           => $payment->consumer_city
			),
			array(
				'id' => $payment->getId()
			),
			array( '%s', '%s', '%s', '%s' ),
			array( '%d' )
		);

        return $result;
    }

	//////////////////////////////////////////////////

    /**
     * Update payment
     * 
     * @param Pronamic_WordPress_IDeal_Payment $payment
     */
	public static function updatePayment( Pronamic_WordPress_IDeal_Payment $payment ) {
		global $wpdb;

		$table = self::getPaymentsTableName();

		$configuration = $payment->configuration;

		$data = array( 
			'configuration_id'        => $configuration->getId(),
			'purchase_id'             => $payment->purchase_id,
			'transaction_id'          => $payment->transaction_id,
			'amount'                  => $payment->amount,
			'currency'                => $payment->currency,
			'expiration_period'       => $payment->expiration_period,
			'language'                => $payment->language,
			'entrance_code'           => $payment->entrance_code,
			'description'             => $payment->description,
			'status'                  => $payment->status,
			'consumer_name'           => $payment->consumer_name,
			'consumer_account_number' => $payment->consumer_account_number,
			'consumer_iban'           => $payment->consumer_iban,
			'consumer_bic'            => $payment->consumer_bic,
			'consumer_city'           => $payment->consumer_city,
			'date_gmt'                => $payment->getDate()->format( 'Y-m-d H:i:s' ),
			'source'                  => $payment->getSource(),
			'source_id'               => $payment->getSourceId() 
		);

		$format = array( 
			'configuration_id'        => '%d',
			'purchase_id'             => '%s',
			'transaction_id'          => '%s',
			'amount'                  => '%F',
			'currency'                => '%s',
			'expiration_period'       => '%s',
			'language'                => '%s',
			'entrance_code'           => '%s',
			'description'             => '%s',
			'status'                  => '%s',
			'consumer_name'           => '%s',
			'consumer_account_number' => '%s',
			'consumer_iban'           => '%s',
			'consumer_bic'            => '%s',
			'consumer_city'           => '%s',
			'date_gmt'                => '%s',
			'source'                  => '%s',
			'source_id'               => '%s' 
		);

		// Insert
        if ( empty( $payment->id ) ) {
            // Insert
            $result = $wpdb->insert( $table, $data, $format );

            if($result !== false) {
            	$payment->setId( $wpdb->insert_id );
            }
        } else {
            // Update
			$result = $wpdb->update( $table, $data, array( 'id' => $payment->getId() ), $format, array( '%d' ) );
        }

        return $result;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the number of payments
	 * 
	 * @return int
	 */
	public static function get_number_payments() {
		global $wpdb;

		$table = self::getPaymentsTableName();

		$number_payments = $wpdb->get_var( "SELECT COUNT(*) FROM $table;" );
		
		return $number_payments;
	}
}
