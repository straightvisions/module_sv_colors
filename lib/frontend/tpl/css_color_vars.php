<style data-sv100_module="<?php echo $this->get_prefix('vars'); ?>">
/* Global Vars */
:root {
/** The Colors are in RGB.
** To use them you need to put them into an rgb()
** or rgba() property.
**
** Examples:
** rgb( var( --sv-100-color-primary ) );
** rgba( var( --sv-100-color-primary ), .5 );
*/
<?php
	foreach($this->get_list() as $slug => $info){
		echo '--sv-100-color-' . $slug . ': ' . $info['color'] . ';' . "\n";
	}
?>
}

/* Color Classes for Gutenberg Support */
<?php
	foreach($this->get_list() as $slug => $info){
		echo '.has-' . $slug . '-background-color { background-color: rgb( var( --sv-100-color-' . $slug . ' ) ); }' . "\n";
		echo '.has-' . $slug . '-color { color: rgb( var( --sv-100-color-' . $slug . ' ) ); }' . "\n";
	}
?>
</style>