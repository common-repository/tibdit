<?php
/**
 * Created by PhpStorm.
 * User: nadil
 * Date: 10/02/16
 * Time: 10:12
 */



require_once('Button.php');

class AdminButton extends Button{
    protected $file_path;
    protected $svg_dom_node;
    protected $button_wrapper;
    protected $imported_svg;

    public function __construct($instance){
        $this->instance = $instance;
        $this->dom_document = new DomDocument();
        $this->style_element = new DomDocument();
        $this->file_path = "https://widget.tibit.com/buttons/tib-btn-".$this->instance['BTN'] .".svg";
        $this->imported_svg = file_get_contents($this->file_path);
        $this->dom_document->loadXML($this->imported_svg);
        $this->generate_id();
        $this->set_style_element();

        $this->dom_document->getElementsByTagName("svg")->item(0);
//        $this->dom_document->getElementsByTagName("svg")->item(0)->setAttribute('width', '100%');
    }

    public function set_style_element( ){

        //insert the style Element to change colour
        $styleElement = $this->style_element->createElement("style",
            ".bd-tib-btn-" .$this->instance['BTN'] ."#" . $this->id ." .bd-btn-backdrop { fill: " .
            $this->instance['BTC'] ." }
			.bd-tib-btn-".$this->instance['BTN']." { height: ". $this->instance['BTH'] . 'px' ." }
		");

        // add the style tag to the end of the document.
        $this->style_element->appendChild($styleElement);
    }

    public function render(){

        $button_html = $this->style_element->saveXML();
        $button_html .= '<button type="button" style="height: ' . $this->instance['BTH'] . 'px"';
        $button_html .= 'class="bd-tib-btn bd-tib-btn-' .$this->instance['BTN'].'"'
        . 'id="' . $this->id . '"'
        .'>'
        .$this->dom_document->saveXML() . '</button>';
        return $button_html;
    }

}