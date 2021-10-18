<style data-sv100_module="<?php echo $this->get_prefix( 'vars' ); ?>">
	/* Global Vars */
	:root {
	/** The Colors are in RGB.
	** To use them you need to put them into an rgb()
	** or rgba() property.
	**
	** Exampl:
	** rgba( var( --sv100_sv_color-green ) );
	*/
	<?php
		foreach ( $this->get_list() as $c ) {
			if ( ! empty( $c['slug'] ) ) {
				echo '--sv100_sv_color-' . $c['slug'] . ': ' . (isset($c['color']) ? $c['color'] : '0,0,0') . ';' . "\n";
			}
		}
	?>
	}

	/* Color Classes for Gutenberg Support */
	<?php
		foreach( $this->get_list() as $c ) {
			if ( ! empty( $c['slug'] ) ) {
				echo '.has-' . $c['slug'] . '-background-color { background-color: rgba( var( --sv100_sv_color-' . $c['slug'] . ' ) ) ; }' . "\n";
				echo '.has-' . $c['slug'] . '-color { color: rgba( var( --sv100_sv_color-' . $c['slug'] . ' ) ) ; }' . "\n";
			}
		}
	?>
</style>