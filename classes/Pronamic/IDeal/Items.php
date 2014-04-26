<?php

/**
 * Title: Items
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_IDeal_Items implements IteratorAggregate {
	/**
	 * The items
	 *
	 * @var array
	 */
	private $items;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize a iDEAL basic object
	 */
	public function __construct() {
		$this->items = array();
	}

	//////////////////////////////////////////////////

	/**
	 * Get iterator
	 *
	 * @see IteratorAggregate::getIterator()
	 */
	public function getIterator() {
		return new ArrayIterator( $this->items );
	}

	//////////////////////////////////////////////////

	/**
	 * Add item
	 */
	public function addItem( Pronamic_IDeal_Item $item ) {
		$this->items[] = $item;
	}

	//////////////////////////////////////////////////

	/**
	 * Calculate the total amount of all items
	 */
	public function get_amount() {
		$amount = 0;

		foreach ( $this->items as $item ) {
			$amount += $item->get_amount();
		}

		return $amount;
	}
}
