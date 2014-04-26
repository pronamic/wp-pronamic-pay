<?php

/**
 * Title: Mollie statuses constants
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 * @see https://www.mollie.nl/support/documentatie/betaaldiensten/ideal/en/
 */
class Pronamic_Gateways_Mollie_Statuses {
	/**
	 * Open
	 *
	 * @var string
	 */
	const OPEN = 'open';

	/**
	 * Cancelled
	 *
	 * @var string
	 */
	const CANCELLED = 'cancelled';

	/**
	 * Paid out
	 *
	 * @var string
	 */
	const PAID_OUT = 'paidout';

	/**
	 * Paid
	 *
	 * @var string
	 */
	const PAID = 'paid';

	/**
	 * Expired
	 *
	 * @var string
	 */
	const EXPIRED = 'expired';

	/////////////////////////////////////////////////

	/**
	 * Transform an Mollie state to an more global status
	 *
	 * @param string $status
	 */
	public static function transform( $status ) {
		switch ( $status ) {
			case Pronamic_Gateways_Mollie_Statuses::OPEN :
				return Pronamic_Pay_Gateways_IDeal_Statuses::OPEN;
			case Pronamic_Gateways_Mollie_Statuses::CANCELLED :
				return Pronamic_Pay_Gateways_IDeal_Statuses::CANCELLED;
			case Pronamic_Gateways_Mollie_Statuses::PAID_OUT :
				return Pronamic_Pay_Gateways_IDeal_Statuses::SUCCESS;
			case Pronamic_Gateways_Mollie_Statuses::PAID :
				return Pronamic_Pay_Gateways_IDeal_Statuses::SUCCESS;
			case Pronamic_Gateways_Mollie_Statuses::EXPIRED :
				return Pronamic_Pay_Gateways_IDeal_Statuses::EXPIRED;
			default:
				return null;
		}
	}
}
