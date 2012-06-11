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
	const PAYMENT_STATUS_INCOMPLETE = 'Incomplete';

	const PAYMENT_STATUS_PENDING = 'Pending';

	const PAYMENT_STATUS_COMPLETED = 'Completed';

	//////////////////////////////////////////////////

	public static function getPaymentDataByAttendeeId($id) {
		event_espresso_require_gateway('process_payments.php');

		$data = array('attendee_id' => $id);

		$data = apply_filters('filter_hook_espresso_prepare_payment_data_for_gateways', $data);
		$data = apply_filters('filter_hook_espresso_get_total_cost', $data);

		return $data; 
	}
}
