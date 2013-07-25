<?php global $config; ?>
<!DOCTYPE html>
<html>
<head>
	<!--meta-->
	<title><?php echo ( $this->getData( 'title' ) ? $this->getData( 'title' ) . ' - ' : '' ) . $config['title']; ?></title>
	<meta charset="UTF-8" />
	<meta name="keywords" content="<?php echo $this->getData( 'keywords' ); ?>" />
	<meta name="description" content="<?php echo $this->getData( 'description' ); ?>" />

	<!--favicon-->
	<link rel="shortcut icon" href="" />
	<link rel="icon" href="" type="image/x-icon" />

	<!--style-->
	<style type="text/css">
		* {
			box-sizing: border-box;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
		}
	</style>

	<!--script-->
	<script type="text/javascript">
	</script>
</head>
<body>

	<h1><a href="<?php echo $this->getData( 'home' ); ?>">home</a></h1>