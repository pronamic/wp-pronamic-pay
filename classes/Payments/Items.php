<?php
/**
 * Items
 *
 * @author    Pronamic <info@pronamic.eu>
 * @copyright 2005-2018 Pronamic
 * @license   GPL-3.0-or-later
 * @package   Pronamic\WordPress\Pay\Payments
 */

namespace Pronamic\WordPress\Pay\Payments;

use ArrayIterator;
use IteratorAggregate;
use Pronamic\WordPress\Money\Money;

/**
 * Items
 *
 * @author Remco Tolsma
 * @version 1.0
 */
class Items implements IteratorAggregate {
	/**
	 * The items.
	 *
	 * @var array
	 */
	private $items;

	/**
	 * Constructs and initialize a iDEAL basic object.
	 */
	public function __construct() {
		$this->items = array();
	}

	/**
	 * Get iterator.
	 *
	 * @see IteratorAggregate::getIterator()
	 */
	public function getIterator() {
		return new ArrayIterator( $this->items );
	}

	/**
	 * Add item.
	 *
	 * @param Item $item The item to add.
	 */
	public function addItem( Item $item ) {
		$this->items[] = $item;
	}

	/**
	 * Calculate the total amount of all items.
	 *
	 * @return Money
	 */
	public function get_amount() {
		$amount = 0;

		foreach ( $this->items as $item ) {
			$amount += $item->get_amount();
		}

		return new Money( $amount );
	}
}
