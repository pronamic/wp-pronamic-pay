<?php 

/**
 * Title: Event Espresso
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_EventEspresso_EventEspresso {
	/**
	 * Payment status incomplete indicator
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_INCOMPLETE = 'Incomplete';

	/**
	 * Payment status pending indicator
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_PENDING = 'Pending';

	/**
	 * Payment status completed indicator
	 * 
	 * @var string
	 */
	const PAYMENT_STATUS_COMPLETED = 'Completed';

	//////////////////////////////////////////////////

	/**
	 * Get payment data by attendee ID
	 * 
	 * @param string $id
	 */
	public static function get_payment_data_by_attendee_id( $id ) {
		event_espresso_require_gateway( 'process_payments.php' );

		$data = array(
			'attendee_id' => $id,
			// The 'txn_details' key is not (always) filled in by the filters 
			// below, to prevent unknown key notices we add it here
			'txn_details' => ''
		);

		$data = apply_filters( 'filter_hook_espresso_prepare_payment_data_for_gateways', $data );
		$data = apply_filters( 'filter_hook_espresso_get_total_cost', $data );

		return $data; 
	}

	//////////////////////////////////////////////////

	/**
	 * Update the payment data
	 * 
	 * @param array $payment_data
	 */
	public static function update_payment( $payment_data ) {
		event_espresso_require_gateway( 'process_payments.php' );
		
		// Apply filter to save payment data in database
		// @see https://github.com/eventespresso/event-espresso-core/blob/event-espresso.3.1.24.1.P/gateways/process_payments.php#L75
		$payment_data = apply_filters( 'filter_hook_espresso_update_attendee_payment_data_in_db', $payment_data );
	}
}
