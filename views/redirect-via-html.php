<!DOCTYPE html>

<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />

		<title><?php _e( 'Redirecting&hellip;', 'pronamic_ideal' ); ?></title>

		<style type="text/css">
			body {
				background: #F6F6F6;
				
				font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
			}
			
			#page {
				margin: 0 auto;

				max-width: 750px;
			}

			#primary {
				background: #FFF;

				border: 1px solid #C3C3C3;

				-webkit-border-radius: 4px;
				   -moz-border-radius: 4px;
				        border-radius: 4px;

				box-shadow: 1px 1px 3px #E7E7E7;

				margin: 30px auto;
				padding: 20px;

				text-align: center;
			}
			
			#primary h1 {
				margin: 0;
			}

			.btn {
				display: inline-block;
				*display: inline;
				padding: 4px 12px;
				margin-bottom: 0;
				*margin-left: .3em;
				font-size: 14px;
				line-height: 20px;
				color: #333333;
				text-align: center;
				text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
				vertical-align: middle;
				cursor: pointer;
				background-color: #f5f5f5;
				*background-color: #e6e6e6;
				background-image: -moz-linear-gradient(top, #ffffff, #e6e6e6);
				background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6));
				background-image: -webkit-linear-gradient(top, #ffffff, #e6e6e6);
				background-image: -o-linear-gradient(top, #ffffff, #e6e6e6);
				background-image: linear-gradient(to bottom, #ffffff, #e6e6e6);
				background-repeat: repeat-x;
				border: 1px solid #cccccc;
				*border: 0;
				border-color: #e6e6e6 #e6e6e6 #bfbfbf;
				border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
				border-bottom-color: #b3b3b3;
				-webkit-border-radius: 4px;
				   -moz-border-radius: 4px;
				        border-radius: 4px;
				filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffffff', endColorstr='#ffe6e6e6', GradientType=0);
				filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
				*zoom: 1;
				-webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
				   -moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
				        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
			}

			.btn-primary {
				color: #ffffff;
				text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
				background-color: #006dcc;
  				*background-color: #0044cc;
				background-image: -moz-linear-gradient(top, #0088cc, #0044cc);
				background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0044cc));
				background-image: -webkit-linear-gradient(top, #0088cc, #0044cc);
				background-image: -o-linear-gradient(top, #0088cc, #0044cc);
				background-image: linear-gradient(to bottom, #0088cc, #0044cc);
				background-repeat: repeat-x;
				border-color: #0044cc #0044cc #002a80;
				border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
				filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0044cc', GradientType=0);
				filter: progid:DXImageTransform.Microsoft.gradient(enabled=false);
			}
			
			#primary .btn {
				border: 0 none;
				border-radius: 6px 6px 6px 6px;
				box-shadow: 0 1px 0 rgba(255, 255, 255, 0.1) inset, 0 1px 5px rgba(0, 0, 0, 0.25);
				color: #FFFFFF;
				font-size: 24px;
				font-weight: 200;
				padding: 10px 20px;
				transition: none 0s ease 0s;
			}
		</style>
	</head>
	
	<?php 
	
	$auto_submit = false;
	$onload      = $auto_submit ? 'document.forms[0].submit();' : '';
	
	?>

	<body onload="<?php esc_attr( $onload ); ?>">
		<div id="page">
			<div id="primary">
				<h1><?php _e( 'Redirecting&hellip;', 'pronamic_ideal' ); ?></h1>

				<p>
					<?php _e( 'You will be automatically redirected to the online payment environment.', 'pronamic_ideal' ); ?>
				</p>

				<p>
					<?php _e( 'Please click the button below if you are not automatically redirected.', 'pronamic_ideal' ); ?>
				</p>

				<?php echo $this->get_form_html( $payment, $auto_submit ); ?>
			</div>
		</div>
	</body>
</html>