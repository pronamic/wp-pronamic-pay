<?php 

/**
 * Title: JobRoller
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_JobRoller_JobRoller {
	/**
	 * Check if JobRoller is active (Automattic/developer style)
	 *
	 * @see https://github.com/Automattic/developer/blob/1.1.2/developer.php#L73
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return defined( 'APP_TD' ) && APP_TD == 'jobroller';
	}
}
