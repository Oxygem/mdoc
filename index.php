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

	//request to be ignored?
	if( in_array( $_GET['request'], $config['ignore'] ) )
		return $tmpl->showError( 'File not found' );

	//empty request = config/home
	if( empty( $_GET['request'] ) )
		$_GET['request'] = $config['home'];

	//request a folder? get index
	if( is_dir( './src/doc/' . $_GET['request'] ) )
		$_GET['request'] .= substr( $_GET['request'], -1 ) == '/' ? 'index' : '/index';

	//does this file exist?
	$file = './src/doc/' . $_GET['request'] . '.md';
	if( !file_exists( $file ) )
		return $tmpl->showError( 'File not found' );

	//set request
	$tmpl->setData( 'request', $_GET['request'] );

	//work out folder
	$bits = explode( '/', $file );
	$filename = str_replace( '.md', '', array_pop( $bits ) );
	$tmpl->setData( 'filename', $filename ); //remove file
	$tmpl->setData( 'title', ucfirst( $filename ) );
	$folder = implode( '/', $bits );
	//get files in current directory, build nav
	$files = glob( $folder . '/*.md' );
	foreach( $files as $key => $f ):
		$files[$key] = str_replace( array( $folder . '/', '.md' ), '', $f );
		if( in_array( $files[$key], $config['ignore'] ) ) unset( $files[$key] );
	endforeach;
	$tmpl->setData( 'filenav', $files );
	//get folders
	$folders = glob( $folder . '/*', GLOB_ONLYDIR );
	foreach( $folders as $key => $f ):
		$folders[$key] = str_replace( $folder . '/', '', $f );
	endforeach;
	$tmpl->setData( 'foldernav', $folders );
	//if request has a slash, up folder link
	if( preg_match( '/\//', $_GET['request'] ) )
		$tmpl->setData( 'subfolder', true );

	//get file
	$data = file_get_contents( $file );

	//hacky, but works: internal page links
	//	replace ](/ (bit in md where link name and url come together) with our location
	$data = preg_replace( '/]\(\//', '](' . $index, $data );

	//similarly hack to above: take top #<key>=<value> for title, description, tags
	preg_match_all( '/\$([aA-zZ0-9]+)=([^\n]+)/i', $data, $matches, PREG_SET_ORDER );
	foreach( $matches as $k => $match ):
		$tmpl->setData( $match[1], $match[2] );
		$data = str_replace( $match[0], '', $data );
	endforeach;

	//another hack (inline lazy-indexes)
	if( preg_match( '/\$=index/i', $data ) ):
		//use file & foldernav to inject cheeky markdown
		$string = '';
		$bit = str_replace( $filename, '', $_GET['request'] );
		if( count( $files ) > 1 ) $string .= '+ **Documents**' . PHP_EOL;
		foreach( $files as $file ):
			if( $file == $filename ) continue;
			$string .= '  + [' . ucfirst( $file ) . '](' . $index . $bit . $file . ')' . PHP_EOL;
		endforeach;
		if( count( $folders ) > 0 ) $string .= '+ **Folders**' . PHP_EOL;
		foreach( $folders as $folder ):
			$string .= '  + [' . ucfirst( $folder ) . '](' . $index . $bit . $folder . '/index)' . PHP_EOL;
		endforeach;
		$data = str_replace( '$=index', $string, $data );
	endif;

	//another hack! (#=home become $index)
	$data = str_replace( '$=home', $index, $data );

	//make markdown
	$data = $md->defaultTransform( $data );

	//show template
	return $tmpl->showPage( $data );
?>