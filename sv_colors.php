<?php

namespace sv100;

class sv_colors extends init {
	public function init() {
		$this->set_module_title( __( 'SV Colors', 'sv100' ) )->set_module_desc( __( 'Define your own color palette.', 'sv100' ) )->set_css_cache_active()->set_section_title( $this->get_module_title() )->set_section_desc( $this->get_module_desc() )->set_section_template_path()->set_section_order( 700 )->set_section_icon( '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M8.997 13.985c.01 1.104-.88 2.008-1.986 2.015-1.105.009-2.005-.88-2.011-1.984-.01-1.105.879-2.005 1.982-2.016 1.106-.007 2.009.883 2.015 1.985zm-.978-3.986c-1.104.008-2.008-.88-2.015-1.987-.009-1.103.877-2.004 1.984-2.011 1.102-.01 2.008.877 2.012 1.982.012 1.107-.88 2.006-1.981 2.016zm7.981-4.014c.004 1.102-.881 2.008-1.985 2.015-1.106.01-2.008-.879-2.015-1.983-.011-1.106.878-2.006 1.985-2.015 1.101-.006 2.005.881 2.015 1.983zm-12 15.847c4.587.38 2.944-4.492 7.188-4.537l1.838 1.534c.458 5.537-6.315 6.772-9.026 3.003zm14.065-7.115c1.427-2.239 5.846-9.748 5.846-9.748.353-.623-.429-1.273-.975-.813 0 0-6.572 5.714-8.511 7.525-1.532 1.432-1.539 2.086-2.035 4.447l1.68 1.4c2.227-.915 2.868-1.04 3.995-2.811zm-12.622 4.806c-2.084-1.82-3.42-4.479-3.443-7.447-.044-5.51 4.406-10.03 9.92-10.075 3.838-.021 6.479 1.905 6.496 3.447l1.663-1.456c-1.01-2.223-4.182-4.045-8.176-3.992-6.623.055-11.955 5.466-11.903 12.092.023 2.912 1.083 5.57 2.823 7.635.958.492 2.123.329 2.62-.204zm12.797-1.906c1.059 1.97-1.351 3.37-3.545 3.992-.304.912-.803 1.721-1.374 2.311 5.255-.591 9.061-4.304 6.266-7.889-.459.685-.897 1.197-1.347 1.586z"/></svg>' )->get_root()->add_section( $this );
		
		// @todo: add to docs: CSS Color Vars
		// --wp--preset--color--$slug
		// .has-$slug-color
		// .has-$slug-background-color
	}
	
	public function theme_json_update_data() {
		$theme_json = $this->theme_json_get_data();
		
		$theme_json['settings']['color']['palette'] = array();
		
		foreach ( $this->get_list( 'hex' ) as $color ) {
			$theme_json['settings']['color']['palette'][ $color['slug'] ] = array(
				'slug'  => $color['slug'],
				'name'  => $color['name'],
				'color' => $color['color']
			);
		}
		
		return $theme_json;
	}
	
	public function get_list( $color_type = 'rgb' ): array {
		$colors  = array();
		$setting = $this->get_setting( 'colors_palette' );
		
		if ( $setting->get_data() ) {
			foreach (
				$this->recursive_change_key( $setting->get_data(), array( 'entry_label' => 'name' ) ) as $group
			) {
				switch ( $color_type ) {
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
					'name'  => $group['name'],
					'slug'  => $this->sanitize_slug( $group['slug'] ),
					'color' => $color_value,
				);
				
			}
		}
		
		return $colors;
	}
	
	private function recursive_change_key( $arr, $set ) {
		if ( is_array( $arr ) && is_array( $set ) ) {
			$newArr = array();
			
			foreach ( $arr as $k => $v ) {
				$key            = array_key_exists( $k, $set ) ? $set[ $k ] : $k;
				$newArr[ $key ] = is_array( $v ) ? $this->recursive_change_key( $v, $set ) : $v;
			}
			
			return $newArr;
		}
		
		return $arr;
	}
	
	public function sanitize_slug( string $slug ): string {
		$slug = sanitize_title_with_dashes( $slug );
		$slug = str_replace( '_', '-', $slug );
		
		return $slug;
	}
	
	protected function register_scripts(): sv_colors {
		parent::register_scripts();
		
		foreach ( $this->get_scripts() as $script ) {
			$script->set_inline();
		}
		
		return $this;
	}
	
	protected function load_settings(): sv_colors {
		$this->get_setting( 'colors_palette' )->set_title( __( 'Color palette', 'sv100' ) )->load_type( 'group' );
		
		$this->get_setting( 'colors_palette' )->run_type()->add_child()->set_ID( 'entry_label' )->set_title( __( 'Name', 'sv100' ) )->set_description( __( 'Give your color a name.', 'sv100' ) )->load_type( 'text' );
		
		$this->get_setting( 'colors_palette' )->run_type()->add_child()->set_ID( 'slug' )->set_title( __( 'Slug', 'sv100' ) )->set_description( __( 'This slug is used for the helper classes.', 'sv100' ) )->load_type( 'text' );
		
		$this->get_setting( 'colors_palette' )->run_type()->add_child()->set_ID( 'color' )->set_title( __( 'Color', 'sv100' ) )->set_default_value( '0,0,0,1' )->load_type( 'color' );
		
		return $this;
	}
}