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

	<!--canonical-->
	<link rel="canonical" href="<?php echo $this->getData( 'home' ); ?><?php echo $this->getData( 'request' ); ?>" />

	<!--style-->
	<link rel="stylesheet" href="<?php echo $this->getData( 'home' ); ?>src/inc/default/style.css"

	<!--script-->
	<script type="text/javascript">
	</script>
</head>
<body>

	<div id="wrap"><div id="innerwrap">
		<div id="header">
			<h1><a href="<?php echo $this->getData( 'home' ); ?>"><?php echo $config['title']; ?> home</a></h1>
		</div><!--end header-->

		<ul id="pagenav">
			<li>Pages in this directory:</li>
			<?php if( $this->getData( 'nav' ) ): foreach( $this->getData( 'nav' ) as $key => $nav ): ?>
				<li><a href="<?php echo $nav; ?>"><?php echo $nav; echo $this->getData( 'filename' ) == $nav ? ' (active)' : ''; ?></a></li>
			<?php endforeach; endif; ?>
		</ul>