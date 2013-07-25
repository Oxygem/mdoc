<?php
	//start time
	$start = microtime( true );

	//get config
	require( 'config.php' );

	//get markdown lib
	require( 'lib/Markdown.php' );
	//get template lib
	require( 'lib/template.php' );



	//init template
	$tmpl = new template( $config['template'], $start );
	//our index location (for internal links/home link)
	$index = str_replace( 'index.php', '', $_SERVER['SCRIPT_NAME'] );
	$tmpl->setData( 'home', $index );

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
	$file = './src/doc/' . $_GET['request'] . '.md';
	if( !file_exists( $file ) )
		return $tmpl->showError( 'File not found' );

	//set request
	$tmpl->setData( 'request', $_GET['request'] );

	//work out folder
	$bits = explode( '/', $file );
	$tmpl->setData( 'filename', str_replace( '.md', '', array_pop( $bits ) ) ); //remove file
	$folder = implode( '/', $bits );
	//get folders + files in current directory, build nav
	$files = glob( $folder . '/*.md' );
	foreach( $files as $key => $f ):
		$files[$key] = str_replace( array( $folder . '/', '.md' ), '', $f );
	endforeach;
	$tmpl->setData( 'nav', $files );

	//get file
	$data = file_get_contents( $file );

	//hacky, but works: internal page links
	//	replace ](/ (bit in md where link name and url come together) with our location
	$data = preg_replace( '/]\(\//', '](' . $index, $data );

	//similarly hack to above: take top #<key>=<value> for title, description, tags
	preg_match_all( '/#([aA-zZ0-9]+)=([aA-zZ0-9, ]+)/i', $data, $matches, PREG_SET_ORDER );
	foreach( $matches as $k => $match ):
		$tmpl->setData( $match[1], $match[2] );
		$data = str_replace( $match[0], '', $data );
	endforeach;

	//make markdown
	$data = $md->defaultTransform( $data );

	//show template
	return $tmpl->showPage( $data );
?>