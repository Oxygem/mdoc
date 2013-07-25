<?php

	//get config
	require( 'config.php' );

	//get markdown lib
	require( 'lib/markdown.php' );
	//get template lib
	require( 'lib/template.php' );

	//init template
	$tmpl = new template( $config['template'] );
	//init markdown
	$md = new Michelf\Markdown();

	//request set?
	if( !isset( $_GET['request'] ) )
		return $tmpl->showError( 'Request not set' );
	//make request only certain chars
	$_GET['request'] = preg_replace( '/[^aA-zZ0-9\/-]/i', '', $_GET['request'] );
	
	//empty request = config/home
	if( empty( $_GET['request'] ) )
		$_GET['request'] = $config['home'];

	//does this file exist?
	$file = __DIR__ . '/src/' . $_GET['request'] . '.md';
	if( !file_exists( $file ) )
		return $tmpl->showError( 'File not found' );

	//get file
	$data = file_get_contents( $file );

	//our index location (for internal links/home link)
	$index = str_replace( 'index.php', '', $_SERVER['SCRIPT_NAME'] );
	$tmpl->setData( 'home', $index );
	//hacky, but works: internal page links
	//	replace ](/ (bit in md where link name and url come together) with our location
	$data = preg_replace( '/]\(\//', '](' . $index, $data );

	//similarly hack to above: take top #<key>=<value> for title, description, tags
	preg_match_all( '/#([aA-zZ0-9]+)=([aA-zZ0-9, ]+)/i', $data, $matches, PREG_SET_ORDER );
	foreach( $matches as $k => $match ):
		$tmpl->setData( $match[1], $match[2] );
		$data = str_replace( $match[0], '', $data );
	endforeach;

	//show template w/ markdown
	return $tmpl->showPage( $md->defaultTransform( $data ) );
?>