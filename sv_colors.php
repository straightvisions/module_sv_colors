<?php
	namespace sv100;
	
	/**
	 * @version         4.000
	 * @author			straightvisions GmbH
	 * @package			sv100
	 * @copyright		2019 straightvisions GmbH
	 * @link			https://straightvisions.com
	 * @since			1.000
	 * @license			See license.txt or https://straightvisions.com
	 */
	
	class sv_colors extends init {
		public function init() {
			$this->set_module_title( 'SV Colors' )
				 ->set_module_desc( __( 'Define your own color palette.', 'sv100' ) )
				 ->load_settings()
				 ->set_section_title( __( 'Colors', 'sv100' ) )
				 ->set_section_desc( __( 'Define your color palette', 'sv100' ) )
				 ->set_section_type( 'settings' )
				 ->get_root()
				 ->add_section( $this );
			
			add_theme_support(
				'editor-color-palette',
				$this->recursive_change_key(
					$this->get_setting( 'colors_palette' )->run_type()->get_data(),
					array( 'entry_label' => 'name' )
				)
			);
			
			add_action( 'wp_footer', array( $this, 'print_css_vars' ) );
		}
		
		protected function load_settings(): sv_colors {
			$this->get_setting( 'colors_palette' )
				 ->set_title( __( 'Color palette', 'sv100' ) )
				 ->set_description( '<p>' .
					__( 'These colors will be available in the Gutenberg-Editor and as helper classes.', 'sv100' ) . '<br><br>
					<u><strong>' . __( 'Text color class', 'sv100' ) . '</strong></u>
					<code style="margin-top: 5px;">.has-<i style="color: #1e1e1e;">slug</i>-color</code><br>
					<u><strong>' . __( 'Background color class', 'sv100' ) . '</strong></u>
					<code style="margin-top: 5px;">.has-<i style="color: #1e1e1e;">slug</i>-background-color</code>' . '</p>'
				 )
				 ->load_type( 'group' );
			
			$this->get_setting( 'colors_palette' )
				 ->run_type()
				 ->add_child( $this )
				 ->set_ID( 'entry_label' )
				 ->set_title( __( 'Name', 'sv100' ) )
				 ->set_description( __( 'Give your color a name.', 'sv100' ) )
				 ->load_type( 'text' );
			
			$this->get_setting( 'colors_palette' )
				 ->run_type()
				 ->add_child( $this )
				 ->set_ID( 'slug' )
				 ->set_title( __( 'Slug', 'sv100' ) )
				 ->set_description( __( 'This slug is used for the helper classes.', 'sv100' ) )
				 ->load_type( 'text' );
			
			$this->get_setting( 'colors_palette' )
				 ->run_type()
				 ->add_child( $this )
				 ->set_ID( 'color' )
				 ->set_title( __( 'Color', 'sv100' ) )
				 ->load_type( 'color' );
			
			return $this;
		}
		
		public function get_list(): array {
			$colors					= array();

			if ( $this->get_setting( 'colors_palette' )->run_type()->get_data() ) {
				foreach ( $this->recursive_change_key(
					$this->get_setting( 'colors_palette' )->run_type()->get_data(),
					array( 'entry_label' => 'name' )
				) as $group ) {
					$colors[ $group['slug'] ]	= array(
						'name'					=> $group['name']
					);

					// Value is a hex color
					if ( preg_match( '/#([a-f0-9]{3}){1,2}\b/i', $group['color'] ) ) {
						if ( hexdec( $group['color'] ) ) {
							list( $r, $g, $b ) = sscanf( $group['color'], "#%02x%02x%02x" );
							
							$colors[ $group['slug'] ]['color']	= $r . ',' . $g . ',' . $b;
						}
					}

					// Value is a rgb color
					elseif ( preg_match( '/(\d{1,3}),(\d{1,3}),(\d{1,3})/ix', str_replace( ' ', '', $group['color'] ) ) ) {
						$colors[ $group['slug'] ]['color']		= str_replace( ' ', '', $group['color'] );
					}

					// Value is invalid
					else {
						$colors[ $group['slug'] ]['color']		= __( 'Invalid color code', 'sv100' );
					}

				}
			}

			return $colors;
		}
		
		public function print_css_vars(){
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