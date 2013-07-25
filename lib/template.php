<?php
	class template {
		private $template;
		private $data = array();
		private $start = 0;

		//construct class
		public function __construct( $template = 'default', $start = 0 ) {
			$this->template = __DIR__ . '/../templates/' . $template . '/';
			$this->start = $start;
		}

		//set data
		public function setData( $key, $value ) {
			$this->data[$key] = $value;
		}

		//get data
		public function getData( $key ) {
			return isset( $this->data[$key] ) ? $this->data[$key] : false;
		}

		//show page (load header + footer around content)
		public function showPage( $content ) {
			include( $this->template . '/header.php' );
			echo $content;
			//set end time for footer
			$this->setData( 'time', round( microtime( true ) - $this->start, 5 ) );
			include( $this->template . '/footer.php' );
		}

		//show error (helper function)
		public function showError( $message ) {
			$this->showPage( '<div class="message error"><strong>Error:</strong> ' . $message . '</div>' );
		}
	}