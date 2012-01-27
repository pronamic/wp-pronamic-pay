<div class="wrap">
	<?php screen_icon(Pronamic_WordPress_IDeal_Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Documentation', Pronamic_WordPress_IDeal_Plugin::TEXT_DOMAIN); ?>
	</h2>

	<?php 
	
	$providers = array(
		'abnamro.nl' => array(
			'name' => 'ABN AMRO' ,
			'url' => 'http://abnamro.nl/' , 
			'resources' => array(
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/iDEALeasy_NL.pdf' ,
					'name' => 'Handleiding IDEAL EASY' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/User_Manual_NL.pdf' ,
					'name' => 'Handleiding iDEAL' ,  
					'version' => '1.17' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Merchant_Integratie_Gids_NL_v2.2.3.pdf' ,
					'name' => 'iDEAL Merchant Integratie gids' ,  
					'version' => '2.2.3' 
				)
			)
		) ,
		'currence.nl' => array(
			'name' => 'Currence' ,
			'url' => 'http://currence.nl/' , 
			'resources' => array(
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/Merchant_Integratie_Gids_2.3_NL.pdf' ,
					'name' => 'iDEAL Merchant Integratie gids' ,  
					'version' => '2.2.3' 
				)
			)
		) ,
		'frieslandbank.nl' => array(
			'name' => 'Friesland Bank' ,
			'url' => 'http://frieslandbank.nl/' , 
			'resources' => array(
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/FRIESLAND_BANK_iDEAL_Manual_php.pdf' ,
					'name' => 'iDEAL Shop Integration Guide – PHP Merchant Plug-in' ,  
					'version' => '0.6' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/FRIESLAND_BANK_iDEAL_Manual_dotnet.pdf' ,
					'name' => 'iDEAL Shop Integration Guide – ASP .Net (C#) Merchant Plug-in' ,  
					'version' => '1.0' 
				)
			)
		) , 
		'ing.nl' => array(
			'name' => 'ING' ,
			'url' => 'http://ing.nl/' , 
			'resources' => array(
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Basic_NL.pdf' ,
					'name' => 'iDEAL Basic – Integratie handleiding' ,
					'version' => '1.3'
				) ,
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Advanced_PHP_EN_V2.2.pdf' ,
					'name' => 'iDEAL Advanced – PHP integration manual' ,  
					'version' => '2.2' 
				) ,
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/Wijzigen_van_een_acquiring_certificaat_in_iDEAL_Advanced_internet_tcm7-82882.pdf' ,
					'name' => 'Wijzigen van een acquiring certificaat in iDEAL Advanced' 
				) ,
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/20100519_iDEAL_Merchant_Integratie_Gids_v2.2.3.pdf' ,
					'name' => 'iDEAL Merchant Integratie gids' ,  
					'version' => '2.2.3' 
				) ,
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/20100519_iDEAL_Merchant_Integration_Guide_v2.2.3.pdf' ,
					'name' => 'iDEAL Merchant Integration Guide' ,  
					'version' => '2.2.3' 
				) ,
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Advanced_PHP_EN_V2.3.pdf' ,
					'name' => 'PHP integration manual - iDEAL advanced' ,  
					'version' => '2.3' 
				) ,
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Advanced_PHP_NL_V2.3.pdf' ,
					'name' => 'Integratiehandleiding PHP voor iDEAL Advanced' ,  
					'version' => '2.3' 
				) ,
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Algemeen_NL_v2.3.pdf' ,
					'name' => 'Introductie en procedure voor iDEAL' ,  
					'version' => '2.3' 
				) ,
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Basic_EN_v2.3.pdf' ,
					'name' => 'Integration manual for iDEAL Basic' ,  
					'version' => '2.3' 
				) ,
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Basic_NL_v2.3.pdf' ,
					'name' => 'Integratiehandleiding voor iDEAL Basic' ,  
					'version' => '2.3' 
				) ,
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Download_guide_EN_v2.2.pdf' ,
					'name' => 'iDEAL Download Guide' ,  
					'version' => '2.2' 
				) ,
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_Downloadwijzer_NL_v2.2.pdf' ,
					'name' => 'iDEAL Downloadwijzer' ,  
					'version' => '2.2' 
				) ,
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/iDEAL_General_EN_v2.3.pdf' ,
					'name' => 'Introduction and procedure for iDEAL' ,  
					'version' => '2.3' 
				) ,
			)
		) ,
		'idealdesk.com' => array(
			'name' => 'iDEALdesk' ,
			'url' => 'https://www.idealdesk.com/' , 
			'resources' => array(
				array(
					'url' => 'http://huisstijl.idealdesk.com/' ,
					'name' => 'Online styleguide van iDEAL' 
				) 
			)
		) , 
		'mollie.nl' => array(
			'name' => 'Mollie' ,
			'url' => 'http://mollie.nl/' , 
			'resources' => array(
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
			'resources' => array(
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/Ogone_eCom_STD_Integration_20041224_EN.pdf' ,
					'name' => 'Ogone Document II: Ogone e-Commerce, integration in the merchant\'s WEB site' 
				) 
			)
		) , 
		'rabobank.nl' => array(
			'name' => 'Rabobank' ,
			'url' => 'http://rabobank.nl/' , 
			'resources' => array(
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/handleiding_ideal_lite_2966321.pdf' ,
					'name' => 'Rabo iDEAL Lite - Winkel Integratie Handleiding' , 
					'version' => '2.3'
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/handleiding_ideal_professional_2966322.pdf' ,
					'name' => 'Handleiding iDEAL Professional' , 
					'version' => '2.1'
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2011/12/kennismaking_rabobank_ideal_dashboard.pdf' ,
					'name' => 'Kennismaking Rabobank iDEAL Dashboard' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/01/rabo_omnikassa_gebruikerhandleiding_dashboard_d1_1_dutch_20120117_final_29420243.pdf' ,
					'name' => 'Gebruikshandleiding Rabo OmniKassa Dashboard' ,  
					'version' => '2.0' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/01/rabo_omnikassa_gebruikshandleiding_downloadsite_29420244.pdf' ,
					'name' => 'Gebruikshandleiding Rabo OmniKassa Downloadsite' ,  
					'version' => '2.0' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/01/rabo_omnikassa_redirect_connector_user_guide_v1_0_10_dutch_final_29420242.pdf' ,
					'name' => 'Integratiehandleiding Rabo OmniKassa Versie 1.0.10 – januari 2012' ,  
					'version' => '1.0.10' 
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
		<?php foreach($provider['resources'] as $file): ?>

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