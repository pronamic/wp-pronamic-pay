<div class="wrap">
	<?php screen_icon(Pronamic_WordPress_IDeal_Plugin::SLUG); ?>

	<h2>
		<?php _e('iDEAL Documentation', 'pronamic_ideal'); ?>
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
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/02/FAQ-NL-V1.17.pdf' ,
					'name' => 'Veelgestelde vragen iDEAL' ,  
					'version' => '1.17' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/02/iDEAL_ABN_AMRO_Integrated_JAVA.pdf' ,
					'name' => 'iDEAL Integrated JAVA - Shop Integration Guide' ,  
					'version' => '1.7' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/02/iDEAL_ABN_AMRO_Integrated_NET.pdf' ,
					'name' => 'iDEAL Integrated Asp.NET - Shop Integration Guide' ,  
					'version' => '1.7' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/02/iDEAL_ABN_AMRO_Integrated_PHP.pdf' ,
					'name' => 'iDEAL Integrated PHP - Shop Integration Guide' ,  
					'version' => '1.7' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/02/Opzegbrief_V2.0.pdf' ,
					'name' => 'Opzegbrief' ,  
					'version' => '2.0' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/02/ABNAMRO_e-Com-ADV_EN.pdf' ,
					'name' => 'e-Commerce Advanced - Technical Integration Guide for e-Commerce' ,  
					'version' => '5.3.3' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/02/ABNAMRO_e-Com-BAS_EN.pdf' ,
					'name' => 'Basic e-Commerce - Technical Integration Guide for e-Commerce' ,  
					'version' => '3.2.2' 
				) , 
				array(
					'url' => 'https://internetkassa.abnamro.nl/ncol/param_cookbook.asp' , 
					'name' => 'Parameter Cookbook'
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
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/09/iDEAL_Merchant_Integratie_Gids_v3.3.1_NL.pdf' ,
					'name' => 'iDEAL Merchant Integratie Gids' ,  
					'version' => '3.3.1' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/09/iDEAL_Merchant_Integratie_Gids__Overzicht_van_Wijzigingen_v3.3.1_NL.pdf' ,
					'name' => 'iDEAL Merchant Integratie Gids - Overzicht van Wijzigingen' ,  
					'version' => '3.3.1' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/09/iDEAL_Merchant_Integration_Guide_Summary_of_Changes_v3.3.1_ENG.pdf' ,
					'name' => 'iDEAL Merchant Integration Guide - Summary of Changes' ,  
					'version' => '3.3.1' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/09/iDEAL-Merchant-Integration-Guide-Mobile-Addendum-EN.pdf' ,
					'name' => 'iDEAL Merchant Integration Guide - Mobile Addendum' ,  
					'version' => '2.2.3' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/09/iDEAL-Merchant-Integratie-Gids-NL.pdf' ,
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
		'ideal.nl' => array(
			'name' => 'iDEAL' ,
			'url' => 'http://ideal.nl/' , 
			'resources' => array(
				array(
					'url' => 'http://www.ideal.nl/' ,
					'name' => 'iDEAL' 
				) , 
				array(
					'url' => 'http://www.ideal.nl/acceptant/?s=banner' ,
					'name' => 'iDEAL-banners die altijd actueel blijven' 
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
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/05/zo_werkt_het_aanvragen_en_aansluiten_van_de_rabo_omnikassa_29417568.pdf' ,
					'name' => 'Zo werkt het aanvragen en aansluiten van de Rabo OmniKassa' ,  
					'date' => new DateTime('17-04-2012') 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/07/integration_guide_rabo_omnikassa_v2_0_1_final_29451215.pdf' ,
					'name' => 'Integration guide Rabo OmniKassa' ,  
					'date' => new DateTime('01-04-2012') , 
					'version' => '2.0.1' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/09/Handleiding-iDEAL-Professional.pdf' ,
					'name' => 'Handleiding iDEAL Professional' ,  
					'date' => new DateTime('01-05-2012') , 
					'version' => '1.1' 
				) , 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/09/Handleiding-iDEAL-Lite.pdf' ,
					'name' => 'Handleiding iDEAL Lite' ,  
					'date' => new DateTime('01-01-2012') , 
					'version' => '2.6' 
				), 
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/09/Integratiehandleiding_Rabo_Omnikassa_v300_nl.pdf',
					'name' => 'Integratiehandleiding Rabo OmniKassa - Versie 3.0 September 2012',  
					'version' => '3.0',
					'date' => new DateTime('01-09-2012')
				),
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/09/Integration_Guide_Rabo_Omnikassa_v300_en.pdf',
					'name' => 'Integration guide Rabo OmniKassa - Version 3.0 September 2012',  
					'version' => '3.0',
					'date' => new DateTime('01-09-2012')
				),
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/09/zo_werkt_het_aanvragen_en_aansluiten_van_de_rabo_omnikassa_29417568.pdf',
					'name' => 'Zo werkt het aanvragen en aansluiten van de Rabo OmniKassa',  
					'date' => new DateTime('03-09-2012')
				)
			)
		) , 
		'sisow.nl' => array(
			'name' => 'Sisow' ,
			'url' => 'http://sisow.nl/' , 
			'resources' => array(
				array(
					'url' => 'http://pronamic.nl/wp-content/uploads/2012/06/Sisow-Pronamic-iDEAL.pdf' ,
					'name' => 'Sisow - Pronamic iDEAL' 
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
				<small><?php printf(__('version %s', 'pronamic_ideal'), $file['version']); ?> </small>
				<?php endif; ?>

				<?php if(isset($file['date'])): ?>
				<small><?php printf(__('%s', 'pronamic_ideal'), $file['date']->format('d-m-Y')); ?> </small>
				<?php endif; ?>
			</a>
		</li>

		<?php endforeach; ?>
	</ul>
	
	<?php endforeach; ?>

	<?php include 'pronamic.php'; ?>
</div>