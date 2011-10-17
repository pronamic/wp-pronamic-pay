<div class="wrap">
	<?php screen_icon(Pronamic_WordPress_IDeal_Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Documentation', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	</h2>

	<?php 
	
	$providers = array(
		'currence.nl' => array(
			'name' => 'Currence' ,
			'url' => 'http://currence.nl/' , 
			'files' => array(
				array(
					'path' => 'docs/currence.nl/Merchant_Integratie_Gids_2.3_NL.pdf' ,
					'name' => 'iDEAL Merchant Integratie gids' ,  
					'version' => '2.2.3' 
				)
			)
		) ,
		'ing.nl' => array(
			'name' => 'ING' ,
			'url' => 'http://ing.nl/' , 
			'files' => array(
				array(
					'path' => 'docs/ing.nl/iDEAL_Basic_NL.pdf' ,
					'name' => 'iDEAL Basic – Integratie handleiding' ,
					'version' => '1.3'
				) ,
				array(
					'path' => 'docs/ing.nl/iDEAL_Advanced_PHP_EN_V2.2.pdf' ,
					'name' => 'iDEAL Advanced – PHP integration manual' ,  
					'version' => '2.2' 
				) ,
				array(
					'path' => 'docs/ing.nl/Wijzigen_van_een_acquiring_certificaat_in_iDEAL_Advanced_internet_tcm7-82882.pdf' ,
					'name' => 'Wijzigen van een acquiring certificaat in iDEAL Advanced' 
				)
			)
		) ,
		'frieslandbank.nl' => array(
			'name' => 'Friesland Bank' ,
			'url' => 'http://frieslandbank.nl/' , 
			'files' => array(
				array(
					'path' => 'docs/frieslandbank.nl/FRIESLAND_BANK_iDEAL_Manual_php.pdf' ,
					'name' => 'iDEAL Shop Integration Guide – PHP Merchant Plug-in' ,  
					'version' => '0.6' 
				) , 
				array(
					'path' => 'docs/frieslandbank.nl/FRIESLAND_BANK_iDEAL_Manual_dotnet.pdf' ,
					'name' => 'iDEAL Shop Integration Guide – ASP .Net (C#) Merchant Plug-in' ,  
					'version' => '1.0' 
				)
			)
		) , 
		'idealdesk.com' => array(
			'name' => 'iDEALdesk' ,
			'url' => 'https://www.idealdesk.com/' , 
			'files' => array(
				array(
					'url' => 'http://huisstijl.idealdesk.com/' ,
					'name' => 'Online styleguide van iDEAL' 
				) 
			)
		) , 
		'mollie.nl' => array(
			'name' => 'Mollie' ,
			'url' => 'http://mollie.nl/' , 
			'files' => array(
				array(
					'url' => 'http://www.mollie.nl/support/documentatie/betaaldiensten/ideal/lite/' ,
					'name' => 'iDEAL Lite/Basic' 
				) , 
				array(
					'url' => 'https://www.mollie.nl/support/documentatie/betaaldiensten/ideal/professional/' ,
					'name' => 'iDEAL Professional/Advanced' 
				)
			)
		) , 
		'ogone.nl' => array(
			'name' => 'Ogone' ,
			'url' => 'http://www.ogone.nl/' ,
			'files' => array(
				array(
					'path' => 'docs/ogone.nl/Ogone_eCom_STD_Integration_20041224_EN.pdf' ,
					'name' => 'Ogone Document II: Ogone e-Commerce, integration in the merchant\'s WEB site' 
				) 
			)
		) , 
		'rabobank.nl' => array(
			'name' => 'Rabobank' ,
			'url' => 'http://rabobank.nl/' , 
			'files' => array(
				array(
					'path' => 'docs/rabobank.nl/handleiding_ideal_lite_2966321.pdf' ,
					'name' => 'Rabo iDEAL Lite - Winkel Integratie Handleiding' , 
					'version' => '2.3'
				) , 
				array(
					'path' => 'docs/rabobank.nl/handleiding_ideal_professional_2966322.pdf' ,
					'name' => 'Handleiding iDEAL Professional' , 
					'version' => '2.1'
				) , 
				array(
					'path' => 'docs/rabobank.nl/kennismaking_rabobank_ideal_dashboard.pdf' ,
					'name' => 'Kennismaking Rabobank iDEAL Dashboard' 
				) 
			)
		)
	);
	
	foreach($providers as $provider): ?>

	<h3>
		<?php echo $provider['name']; ?>
		<small><a href="<?php echo $provider['url']; ?>"><?php echo $provider['url']; ?></a></small>
	</h3>

	<ul>
		<?php foreach($provider['files'] as $file): ?>

		<li>
			<?php 
			
			$href = null;

			if(isset($file['path'])) {
				$href = plugins_url($file['path'], Pronamic_WordPress_IDeal_Plugin::$file);
			}

			if(isset($file['url'])) {
				$href = $file['url'];
			}
			
			?>
			<a href="<?php echo $href; ?>">
				<?php echo $file['name']; ?>

				<?php if(isset($file['version'])): ?>
				<small><?php printf(__('version %s', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN), $file['version']); ?> </small>
				<?php endif; ?>
			</a>
		</li>

		<?php endforeach; ?>
	</ul>
	
	<?php endforeach; ?>
</div>