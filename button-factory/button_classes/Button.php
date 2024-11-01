<?php 
class Button {



	protected $width, $height;
	protected $dom_document;
	protected $button_type;
	protected $base_colour;
	protected $id;
	protected $style_element;
	protected $tibdit_options;
	protected $instance;

	// Creates the DOM and loads the SVG for additonal information to be added e.g. CSS
	public function __construct($instance, $shortcode_atts){
		$this->instance = $instance;
		if($shortcode_atts){
			$this->shortcode_atts = $shortcode_atts;
		}
		$this->tibdit_options = get_option('tibdit_options');
		$this->style_element = new DomDocument();
		$this->dom_document = new DomDocument();
		$this->dom_document->loadHTML($this->create_button_skeleton());
		$this->generate_id();
		$this->set_style_element();

		$this->cycle_data_bd_param();

	}



	/**
	 * @return string
     */
	protected function create_button_skeleton(){
		$button_skeleton = "<button class='bd-tib-btn'></button>";
		return $button_skeleton;
	}

	/*
	* Generates a random alpha-numeric string and adds it as a class of the SVG document.
	* This enables us to style buttons independently of one-another.
	*/
	public function generate_id(){
		$characters = 'abcdefghijklmnopqrstuvwxyz';
		$string = '';
		for ($i = 0; $i < strlen($characters); $i++) {
			$string .= $characters[rand(0, strlen($characters) - 1)];
		}
		$this->set_id("tibdit_{$string}");
	}

	public function set_data_bd_param($KEY, $VAL=null){

			if(isset($VAL)) {
				$this->dom_document->getElementsByTagName('button')->item(0)->setAttribute('data-bd-'.$KEY,
					$VAL);
			}
			elseif(isset($this->instance[$KEY])){
				$this->dom_document->getElementsByTagName('button')->item(0)->setAttribute('data-bd-'.$KEY,
				$this->instance[$KEY]);
			}
	}

	protected function cycle_data_bd_param(){
		$this->set_data_bd_param('SUB');

		if(get_permalink() && in_the_loop()){
			$this->set_data_bd_param('TIB', get_permalink());
		}
		else{
			$this->set_data_bd_param('TIB', get_home_url());
		}

		$this->set_data_bd_param('BTN', $this->instance['BTN']);

		if(isset($this->shortcode_atts)){
			foreach($this->shortcode_atts as $key => $value){
				if($value){
					$this->set_data_bd_param($key);
				}
			}
		}
	}



	public function set_id( $id ){
		$this->id = $id;
		if($this->dom_document->getElementsByTagName("button")->length){
			$this->dom_document->getElementsByTagName("button")->item(0)->setAttribute("id", $id);
		}

	}

	public function set_style_element( ){

        //insert the style Element to change colour
		$styleElement = $this->style_element->createElement("style", "
			#". $this->id .".bd-tib-btn-" .$this->instance['BTN'] ." .bd-btn-backdrop { fill: " .
			$this->instance['BTC'] ." }
			#". $this->id .".bd-tib-btn-".$this->instance['BTN']." { height: ". $this->instance['BTH'] . 'px' ." }
		");

        // add the style tag to the end of the document.
		$this->style_element->appendChild($styleElement);
	}

	// Prints the SVG file that was loaded to the screen
	public function render() {
		$button_html = $this->style_element->saveXML();
		$button_html .= $this->dom_document->saveHTML();

		return $button_html;
	}

}
?>