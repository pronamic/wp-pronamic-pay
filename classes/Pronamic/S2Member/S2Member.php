<?php 

/**
 * Title: s2Member
 * Description: 
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Remco Tolsma
 * @version 1.0
 */
class Pronamic_S2Member_S2Member {
	/**
	 * Check if 22Member is active (Automattic/developer style)
	 *
	 * @see https://github.com/WebSharks/s2Member/blob/130816/s2member/s2member.php#L69
	 * @see https://github.com/Automattic/developer/blob/1.1.2/developer.php#L73
	 *
	 * @return boolean
	 */
	public static function is_active() {
		return defined( 'WS_PLUGIN__S2MEMBER_VERSION' );
	}
}
