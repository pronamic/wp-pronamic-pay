<?php

/**
 * Title: Feeds repository
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_GravityForms_IDeal_FeedsRepository {
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

        // Feed table
        $feeds_table = self::getFeedsTableName();

        $sql = "CREATE TABLE $feeds_table (
			id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			form_id MEDIUMINT(8) UNSIGNED NOT NULL,
			configuration_id MEDIUMINT(8) UNSIGNED NOT NULL,
			is_active TINYINT(1) NOT NULL DEFAULT 1,
			meta LONGTEXT,
			PRIMARY KEY  (id),
			KEY form_id (form_id),
			KEY configuration_id (configuration_id)
			) $charset_collate;";

        dbDelta( $sql );
    }

	//////////////////////////////////////////////////

    /**
     * Get the iDEAL feeds table name
     * 
     * @return string
     */
    public static function getFeedsTableName() {
		global $wpdb;

		return $wpdb->prefix . 'rg_ideal_feeds';
    }

	//////////////////////////////////////////////////

    /**
     * Get feed by the specified result object
     * 
     * @param stdClass $result
     */
    private function getFeedByResult( $result ) {
		$feed = new Pronamic_GravityForms_IDeal_Feed();
       	
       	$feed->id     = $result->id;
       	$feed->formId = $result->formId;
       	$feed->title  = $result->title;
       	$feed->setIDealConfiguration( Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationById( $result->configurationId ) );

		$meta = json_decode( $result->meta );

       	$feed->transactionDescription = $meta->transactionDescription;
		$feed->links                  = (array) $meta->links;
		$feed->delayNotificationIds   = isset( $meta->delayNotificationIds ) ? $meta->delayNotificationIds : array();
		$feed->delayAdminNotification = $meta->delayAdminNotification;
		$feed->delayUserNotification  = $meta->delayUserNotification;
		$feed->delayPostCreation      = $meta->delayPostCreation;
		$feed->conditionEnabled       = $meta->conditionEnabled;
		$feed->conditionFieldId       = $meta->conditionFieldId;
		$feed->conditionOperator      = $meta->conditionOperator;
		$feed->conditionValue         = $meta->conditionValue;
		$feed->userRoleFieldId        = $meta->userRoleFieldId;

		return $feed;
    }
	
    /**
     * Get feed query
     * 
     * @param string $extra
     */
	private function getFeedQuery($extra = '') {
        $feedsTable = self::getFeedsTableName();
        $configurationsTable = Pronamic_WordPress_IDeal_ConfigurationsRepository::getConfigurationsTableName();
        $formsTable = RGFormsModel::get_form_table_name();

        $query = sprintf("
        	SELECT 
        		feed.id AS id,
        		feed.form_id AS formId ,  
        		feed.configuration_id AS configurationId , 
        		feed.is_active AS isActive , 
        		feed.meta AS meta , 
        		form.title AS title 
			FROM 
	        	$feedsTable AS feed
	                LEFT JOIN 
	        	$formsTable AS form
	        			ON feed.form_id = form.id
	                LEFT JOIN 
	        	$configurationsTable AS configuration
	        			ON feed.configuration_id = form.id
			%s
        ", $extra);

		return $query;
	}

	//////////////////////////////////////////////////

    /**
     * Get the iDEAL feeds
     * 
     * @return array
     */
    public static function getFeeds() {
		global $wpdb;

		$feeds = array();

		$results = $wpdb->get_results(self::getFeedQuery(), OBJECT_K);
		foreach($results as $result) {
			$feeds[] = self::getFeedByResult($result);
		}

        return $feeds;
    }

	//////////////////////////////////////////////////

    /**
     * Get the iDEAL feed by the specified query
     * 
     * @param string $query
     */
    private static function getFeedByQuery($query) {
		global $wpdb;

        $feed = null;

        $result = $wpdb->get_row($query, OBJECT);
        if($result != null) {
        	$feed = self::getFeedByResult($result);
        }

        return $feed;
    }

	//////////////////////////////////////////////////

    /**
     * Get the iDEAL feed by the specified form ID
     * 
     * @param string $id
     * @param boolean $active
     */
    public static function getFeedById($id, $active = true) {
		global $wpdb;

        $activeClause = $active ? ' AND feed.is_active' : '';

        $query = $wpdb->prepare( self::getFeedQuery("
        	WHERE 
        		feed.id = %d 
        		$activeClause
        	"), $id
        );

        return self::getFeedByQuery($query);
    }

	//////////////////////////////////////////////////

    /**
     * Get the iDEAL feed by the specified form ID
     * 
     * @param string $id
     * @param boolean $active
     * @return Pronamic_GravityForms_IDeal_Feed
     */
    public static function getFeedByFormId($id, $active = true) {
		global $wpdb;

        $activeClause = $active ? ' AND feed.is_active' : '';

        $query = $wpdb->prepare( self::getFeedQuery("
        	WHERE 
        		feed.form_id = %d 
        		$activeClause
        	"), $id
        );

        return self::getFeedByQuery($query);
    }

	//////////////////////////////////////////////////

	/**
	 * Get the available forms for iDEAL
	 * 
	 * @param string $activeFormId
	 */
    public static function getAvailableForms($allowedId = null) {
        $availableForms = array();

        $feeds = self::getFeeds();
        $forms = RGFormsModel::get_forms();

        foreach($forms as $form) {
			if($form->id == $allowedId || !self::isIDealFeed($form->id, $feeds)) {
                $availableForms[] = $form;
			}
        }

        return $availableForms;
    }

    /**
     * Check if the specified form ID is an iDEAL feed
     * 
     * @param string $id
     */
    public static function isIDealFeed($id, array $feeds = null) {
    	if($feeds == null) {
        	$feeds = self::getFeeds();
    	}

        foreach($feeds as $feed) {
            if($feed->formId == $id) {
				return true;
			}
        }

        return false;
    }

	//////////////////////////////////////////////////

    /**
     * Update feed
     * 
     * @param string $id
     */
    public static function updateFeed($feed) {
		global $wpdb;

		$feed_table = self::getFeedsTableName();

		$configurationId = $feed->getIDealConfiguration() == null ? null : $feed->getIDealConfiguration()->getId();

		$data = array(
			'form_id' => $feed->formId , 
			'configuration_id' => $configurationId , 
			'is_active' => $feed->isActive , 
			'meta' => json_encode($feed)
		);

		$format = array('%d', '%d', '%d', '%s', '%s');

		if(empty($feed->id)) {
			// Insert
			$result = $wpdb->insert( $feed_table, $data, $format);

			if($result !== false) {
				$feed->id = $wpdb->insert_id;
			}
        } else {
            // Update
			$result = $wpdb->update( $feed_table, $data, array('id' => $feed->id), $format, array('%d'));
        }

        return $feed->id;
    }

	//////////////////////////////////////////////////

    /**
     * Delete feeds by the specified IDs
     * 
     * @param array $ids
     */
	public static function deleteFeeds(array $ids) {
		global $wpdb;

		$feeds_table = self::getFeedsTableName();
		
		$list = implode( ',', array_map( 'absint', $ids ) );

		$query = "
			DELETE 
			FROM 
				$feeds_table 
			WHERE 
				id IN ($list)
		";

        return $wpdb->query($query);
    }
}
