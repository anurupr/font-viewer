<?php

class Cssify{

	/*
	 * Font formats used in defining a font family
	 */

	var $font_file_types = array(
	                    'ttf'=>"truetype",
	                    'otf'=>"opentype",
	                    'eot'=>'embedded-opentype',
	                    'svg'=>'svg',
	                    'woff'=>'woff',
	                    'woff2'=>'woff2'
	                   );


	/*
	 * Default options for font family	 
	 */



	var $options = array(
						'font-weight' => 'normal',
						'font-style' => 'normal',
						
					);


	/*
	 * $font_family_header
	 */

	var $font_family_header = "@font-face{";

	/*
	 * $font_family_footer
	 */

	var $font_family_footer = "}";


	/*
	 * font family body
	 */
	var $font_family_body = "";


	/*
	 * font data
	 */

	var $font_data = array();



	private function get_font_file_type($font_ext){
		if(empty($font_ext)){
			return null;
		}

		if(isset($this->font_file_types[$font_ext])){
			return $this->font_file_types[$font_ext];
		}
		else if(isset($this->font_file_types[pathinfo($font_ext,PATHINFO_EXTENSION)])){
			return $this->font_file_types[pathinfo($font_ext,PATHINFO_EXTENSION)];
		}
	}


	private function get_font_family_body(){

		return $this->font_family_body;

	}

	private function set_font_family_body(){

		

		$this->font_family_body .= "font-family:\"".$this->font_data['name']."\";";
		$this->font_family_body .= "src:url('".$this->font_data['path']."') format('".$this->get_font_file_type($this->font_data['filename'])."');";

		// options [ default ]
		$this->font_family_body .= $this->css_serialize_font_options();

	}

	private function css_serialize_font_options($options=NULL){
		if(is_null($options)){
			$default_options_css = "";
			$keys = array_keys($this->options);
			foreach($keys as $key){
				$default_options_css .= $key.":".$this->options[$key].";";
			}

			return $default_options_css;
		}
	}

	public function build_font_family_string(){

		$font_family_string = "";


		$font_family_string .= $this->font_family_header;

		if(empty($this->font_family_body))
			$this->set_font_family_body();

		$font_family_string .= $this->font_family_body;
		$font_family_string .= $this->font_family_footer;

		return $font_family_string;
	}

	public function set_font_data($font_data){
		$this->font_data = $font_data;
	}

}
?>