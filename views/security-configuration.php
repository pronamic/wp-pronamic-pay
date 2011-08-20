<h3>
	<?php _e('Security configuration', Gravity_Forms_IDeal::TEXT_DOMAIN); ?>
</h3>

<p>
	<?php _e('In order to create a private/public key pair that can be used by the plugin, the following steps have to be completed:', Gravity_Forms_IDeal::TEXT_DOMAIN); ?>
</p>

<ul>
	<li>
		<p>
			<?php _e('Download the openssl library to your computer (<a href="http://www.openssl.org/">http://www.openssl.org</a>). There are a number of binaries for different operating systems available.', Gravity_Forms_IDeal::TEXT_DOMAIN); ?>
		</p>
	</li>
	<li>
		<p>
			<?php _e('Generate a RSA private key:', Gravity_Forms_IDeal::TEXT_DOMAIN); ?>
		</p>
		<p>
			<pre>openssl genrsa -des3 –out <strong>priv.pem</strong> -passout pass:<strong><?php _e('password', Gravity_Forms_IDeal::TEXT_DOMAIN); ?></strong> 1024</pre>
		</p>
	</li>
	<li>
		<p>
			<?php _e('Create a new Certificate based on this private key:', Gravity_Forms_IDeal::TEXT_DOMAIN); ?>
		</p>
		<p>
			<pre>openssl req -x509 -new -key <strong>priv.pem</strong> -passin pass:<strong><?php _e('password', Gravity_Forms_IDeal::TEXT_DOMAIN); ?></strong> -days 365 -out cert.crt</pre>
		</p>
	</li>	
</ul>