:root {
<?php
	// CSS vars
	foreach ( $module->get_list() as $c ) {
		if ( ! empty( $c['slug'] ) ) {
			echo '--sv100_sv_color-' . $c['slug'] . ': ' . (isset($c['color']) ? $c['color'] : '0,0,0') . ';' . "\n";
		}
	}
?>
}
<?php
	// Gutenberg classes
	foreach( $module->get_list() as $c ) {
		if ( ! empty( $c['slug'] ) ) {
			echo '.has-' . $c['slug'] . '-background-color { background-color: rgba( var( --sv100_sv_color-' . $c['slug'] . ' ) ) !important; }' . "\n";
			echo '.has-' . $c['slug'] . '-color{ color: rgba( var( --sv100_sv_color-' . $c['slug'] . ' ) ) !important; }' . "\n";
		}
	}
?>