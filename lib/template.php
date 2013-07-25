<?php
	class template {
		private $template;
		private $data = array();

		//construct class
		public function __construct( $template = 'default' ) {
			$this->template = __DIR__ . '/../templates/' . $template . '/';
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
			include( $this->template . '/footer.php' );
		}

		//show error (helper function)
		public function showError( $message ) {
			$this->showPage( '<div class="message error"><strong>Error:</strong> ' . $message . '</div>' );
		}

		//show warning (helper function)
		public function showWarning( $message ) {
			$this->showPage( '<div class="message warning"><strong>Warning:</strong> ' . $message . '</div>' );
		}
	}