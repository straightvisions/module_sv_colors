<?php
	namespace sv_100;
	
	/**
	 * @version         1.00
	 * @author			straightvisions GmbH
	 * @package			sv_100
	 * @copyright		2017 straightvisions GmbH
	 * @link			https://straightvisions.com
	 * @since			1.0
	 * @license			See license.txt or https://straightvisions.com
	 */
	
	class sv_colors extends init {
		public function __construct() {
		
		}
		
		private function recursive_change_key($arr, $set) {
			if (is_array($arr) && is_array($set)) {
				$newArr = array();
				foreach ($arr as $k => $v) {
					$key = array_key_exists( $k, $set) ? $set[$k] : $k;
					$newArr[$key] = is_array($v) ? $this->recursive_change_key($v, $set) : $v;
				}
				return $newArr;
			}
			return $arr;
		}
		
		public function init() {
			// Translates the module
			load_theme_textdomain( $this->get_module_name(), $this->get_path( 'languages' ) );
			
			// Module Info
			$this->set_module_title( 'SV Colors' );
			$this->set_module_desc( __( 'This module allows you to define your own color palette.', $this->get_module_name() ) );
			
			// Section Info
			$this->set_section_title( 'Colors' );
			$this->set_section_desc( __( 'Color Settings', $this->get_module_name() ) );
			$this->set_section_type( 'settings' );
			$this->get_root()->add_section( $this );
			
			$this->s['colors_palette'] =
				static::$settings->create( $this )
								 ->set_ID( 'colors_palette' )
								 ->set_title( __( 'Color Palettes', $this->get_module_name() ) )
								 ->set_description( __( 'These colors will also be available in Gutenberg-Editor.', $this->get_module_name() ) )
								 ->load_type( 'group' );
			
			$name						= $this->s['colors_palette']
				->run_type()
				->add_child( $this )
				->set_ID( 'entry_label' )
				->set_title( __( 'Color Name', $this->get_module_name() ) )
				->set_description( __( 'This Name is used to identify this color for users.', $this->get_module_name() ) )
				->load_type( 'text' )
				->set_placeholder( 'Dark Gray' );
			
			$this->s['colors_palette']
				->run_type()
				->add_child( $this )
				->set_ID( 'slug' )
				->set_title( __( 'Color Slug', $this->get_module_name() ) )
				->set_description( __( 'This Slug is used to identify this color within code.', $this->get_module_name() ) )
				->load_type( 'text' )
				->set_placeholder( 'dark-gray' );
			
			$this->s['colors_palette']
				->run_type()
				->add_child( $this )
				->set_ID( 'color' )
				->set_title( __( 'Color Value', $this->get_module_name() ) )
				->set_description( __( 'The color to be used.', $this->get_module_name() ) )
				->load_type( 'text' )
				->set_placeholder( 'rgb(60,60,60)' );
			
			add_theme_support(
				'editor-color-palette',
				$this->recursive_change_key(
					$this->s['colors_palette']->run_type()->get_data(),
					array('entry_label' => 'name')
				)
			);
		}
		
		public function get_list(): array{
			$colors					= array();
			
			foreach($this->recursive_change_key(
				$this->s['colors_palette']->run_type()->get_data(),
				array('entry_label' => 'name')
			) as $group){
				$colors[$group['slug']]	= array(
					'name'				=> $group['name']
				);
				
				if(hexdec($group['color'])){
					list($r, $g, $b) = sscanf($group['color'], "#%02x%02x%02x");
					$colors[$group['slug']]['color']	= $r.','.$g.','.$b;
				}
				
			}
			return $colors;
		}
	}