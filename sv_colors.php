<?php
	namespace sv100;
	
	/**
	 * @version         4.010
	 * @author			straightvisions GmbH
	 * @package			sv100
	 * @copyright		2019 straightvisions GmbH
	 * @link			https://straightvisions.com
	 * @since			1.000
	 * @license			See license.txt or https://straightvisions.com
	 */
	
	class sv_colors extends init {
		public function init() {
			$this->set_module_title( __( 'SV Colors', 'sv100' ) )
				 ->set_module_desc( __( 'Define your own color palette.', 'sv100' ) )
				 ->load_settings()
				 ->set_section_title( __( 'Colors', 'sv100' ) )
				 ->set_section_desc( __( 'Define your color palette', 'sv100' ) )
				 ->set_section_type( 'settings' )
				 ->get_root()
				 ->add_section( $this );
			
			add_theme_support(
				'editor-color-palette',
				$this->get_list( 'hex' )
			);
			
			add_action( 'wp_footer', array( $this, 'print_css_vars' ) );
		}
		
		protected function load_settings(): sv_colors {
			$this->get_setting( 'colors_palette' )
				 ->set_title( __( 'Color palette', 'sv100' ) )
				 ->set_description( '<p>' .
									__( 'These colors will be available in the Gutenberg-Editor and as helper classes.', 'sv100' )
									. '<br><br>
					<u><strong>' . __( 'Text color class', 'sv100' ) . '</strong></u>
					<code style="margin-top: 5px;">.has-<i style="color: #1e1e1e;">slug</i>-color</code><br>
					<u><strong>' . __( 'Background color class', 'sv100' ) . '</strong></u>
					<code style="margin-top: 5px;">.has-<i style="color: #1e1e1e;">slug</i>-background-color</code>' . '</p>'
				 )
				 ->load_type( 'group' );
			
			$this->get_setting( 'colors_palette' )
				 ->run_type()
				 ->add_child()
				 ->set_ID( 'entry_label' )
				 ->set_title( __( 'Name', 'sv100' ) )
				 ->set_description( __( 'Give your color a name.', 'sv100' ) )
				 ->load_type( 'text' );
			
			$this->get_setting( 'colors_palette' )
				 ->run_type()
				 ->add_child()
				 ->set_ID( 'slug' )
				 ->set_title( __( 'Slug', 'sv100' ) )
				 ->set_description( __( 'This slug is used for the helper classes.', 'sv100' ) )
				 ->load_type( 'text' );
			
			$this->get_setting( 'colors_palette' )
				 ->run_type()
				 ->add_child()
				 ->set_ID( 'color' )
				 ->set_title( __( 'Color', 'sv100' ) )
				->set_default_value(0,0,0,1)
				 ->load_type( 'color' );
			
			return $this;
		}
		
		public function get_list( $color_type = 'rgb' ): array {
			$colors		= array();
			$setting 	= $this->get_setting( 'colors_palette' );
			
			if ( $setting->get_data() ) {
				foreach ( $this->recursive_change_key(
					$setting->get_data(),
					array( 'entry_label' => 'name' )
				) as $group ) {
					switch( $color_type ) {
						case 'hex':
							$color_value = isset( $group['color'] ) ? $setting->get_hex( $group['color'] ) : '#000000';
							break;
						case 'rgb':
						case 'rgba':
						default:
							$color_value = isset( $group['color'] ) ? $setting->get_rgb( $group['color'] ) : '0,0,0,1';
							break;
					}
					
					$colors[] = array(
						'name'	=> $group['name'],
						'slug'	=> $group['slug'],
						'color'	=> $color_value,
					);
					
				}
			}
			
			return $colors;
		}
		
		public function print_css_vars() {
			require_once( $this->get_path( 'lib/frontend/tpl/css_color_vars.php' ) );
		}
		
		private function recursive_change_key( $arr, $set ) {
			if ( is_array( $arr ) && is_array( $set ) ) {
				$newArr = array();
				
				foreach ( $arr as $k => $v ) {
					$key = array_key_exists( $k, $set) ? $set[ $k ] : $k;
					$newArr[ $key ] = is_array( $v ) ? $this->recursive_change_key( $v, $set ) : $v;
				}
				
				return $newArr;
			}
			
			return $arr;
		}
	}