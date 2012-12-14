<?php

/**
 * Title: Feed
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_GravityForms_IDeal_Feed {
	/**
	 * Indicator for the open status link
	 * 
	 * @var string
	 */
	const LINK_OPEN = 'open';

	/**
	 * Indicator for the cancel status link
	 * 
	 * @var string
	 */
	const LINK_CANCEL = 'cancel';

	/**
	 * Indicator for the error status link
	 * 
	 * @var string
	 */
	const LINK_ERROR = 'error';

	/**
	 * Indicator for the success status link
	 * 
	 * @var string
	 */
	const LINK_SUCCESS = 'success';

	/**
	 * Indicator for the expired status link
	 * 
	 * @var string
	 */
	const LINK_EXPIRED = 'expired';

	//////////////////////////////////////////////////

	/**
	 * Indicator for an link to an WordPress page
	 * 
	 * @var string
	 */
	const LINK_TYPE_PAGE = 'page';

	/**
	 * Indicator for an link to an URL
	 * 
	 * @var string
	 */
	const LINK_TYPE_URL = 'url';

	//////////////////////////////////////////////////

	/**
	 * The unique id of this feed
	 * 
	 * @var string
	 */
	public $id;
	
	/**
	 * The iDEAL configuration
	 * 
	 * @var Configuration
	 */
	private $iDealConfiguration;

	/**
	 * The form ID
	 * 
	 * @var string
	 */
	public $formId;

	/**
	 * Flag  for an active or inactive feed
	 * 
	 * @var boolean
	 */
	public $isActive;
	
	/**
	 * The transaction description template
	 * 
	 * @var string
	 */
	public $transactionDescription;

	//////////////////////////////////////////////////

	/**
	 * Delay admin notification
	 * 
	 * @var boolean
	 */
	public $delayAdminNotification;

	/**
	 * Delay user notification
	 * 
	 * @var boolean
	 */
	public $delayUserNotification;

	/**
	 * Delay post creation
	 * 
	 * @var string
	 */
	public $delayPostCreation;

	//////////////////////////////////////////////////
	
	/**
	 * Flag for enabled or disabled condition
	 * 
	 * @var boolean
	 */
	public $conditionEnabled;
	
	/**
	 * The condition Gravity Forms field ID
	 * 
	 * @var string
	 */
	public $conditionFieldId;

	/**
	 * The condition operator (is or is not)
	 * 
	 * @var string
	 */
	public $conditionOperator;

	/**
	 * The condition value
	 * 
	 * @var string
	 */
	public $conditionValue;

	//////////////////////////////////////////////////

	/**
	 * The user role Gravity Forms field ID
	 * 
	 * @var string
	 */
	public $userRoleFieldId;

	//////////////////////////////////////////////////

	/**
	 * The links
	 * 
	 * @var array
	 */
	public $links;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initialize an GravityForms iDEAL feed
	 */
	public function __construct() {
		$this->links = array();
		$this->isActive = true;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the unique ID of this iDEAL feed
	 * 
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the iDEAL configuration object
	 * 
	 * @return Configuration
	 */
	public function getIDealConfiguration() {
		return $this->iDealConfiguration;
	}

	/**
	 * Set the iDEAL configuration object
	 * 
	 * @param Configuration $iDealConfiguration
	 */
	public function setIDealConfiguration( Pronamic_WordPress_IDeal_Configuration $iDealConfiguration = null ) {
		$this->iDealConfiguration = $iDealConfiguration;
	}

	//////////////////////////////////////////////////

	/**
	 * Get the links of this feed
	 * 
	 * @return array
	 */
	public function getLinks() {
		return $this->links;
	}

	/**
	 * Set the links of this feed
	 * 
	 * @param array $links
	 */
	public function setLinks( array $links ) {
		$this->links = $links;
	}
	
	/**
	 * Get the URL of the specified name
	 * 
	 * @param string $name
	 */
	public function getUrl( $name ) {
		$url = null;

		$link = $this->getLink( $name );

		if ( $link != null ) {
			// link is een standard class object, the type variable could not be defined
			if ( isset( $link->type ) ) {
				switch ( $link->type ) {
					case self::LINK_TYPE_PAGE:
						$url = get_permalink( $link->pageId );
						break;
					case self::LINK_TYPE_URL:
						$url = $link->url;
						break;
				}
			}
		}

		if ( empty ( $url ) ) {
			$url = site_url( '/' );
		}
		
		return $url;
	}

	/**
	 * Get the link of the specified name
	 * 
	 * @param string $name
	 */
	public function getLink( $name ) {
		$link = null;

		if ( isset( $this->links[$name] ) ) {
			$link = $this->links[$name];
		}
		
		return $link;
	}

	/**
	 * Set the link of the specified name
	 * 
	 * @param string $name
	 * @param stdClass $link
	 */
	public function setLink( $name, $link ) {
		$this->links[$name] = $link;
	}
}
