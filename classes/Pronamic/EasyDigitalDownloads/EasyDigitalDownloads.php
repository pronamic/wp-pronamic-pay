<?php

/**
 * Title: Easy Digital Downloads
 * Description:
 * Copyright: Copyright (c) 2005 - 2011
 * Company: Pronamic
 * @author Stefan Boonstra
 * @version 1.0
 */
class Pronamic_EasyDigitalDownloads_EasyDigitalDownloads {
    /**
     * Order status pending
     *
     * @var string
     */
    const ORDER_STATUS_PENDING = 'pending';

    /**
     * Order status completed
     *
     * @var string
     */
    const ORDER_STATUS_PUBLISH = 'publish';

    /**
     * Order status refunded
     *
     * @var string
     */
    const ORDER_STATUS_REFUNDED = 'refunded';

    /**
     * Order status failed
     *
     * @var string
     */
    const ORDER_STATUS_FAILED = 'failed';

    /**
     * Order status abandoned
     *
     * @var string
     */
    const ORDER_STATUS_ABANDONED = 'abandoned';

    /**
     * Order status revoked/cancelled
     *
     * @var string
     */
    const ORDER_STATUS_REVOKED = 'revoked';

    //////////////////////////////////////////////////

    /**
     * Check if Easy Digital Downloads is active
     *
     * @return boolean
     */
    public static function is_active() {
        return defined( 'EDD_VERSION' );
    }
}
