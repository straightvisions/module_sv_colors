<style data-sv_100_module="<?php echo $this->get_prefix('vars'); ?>">
/* Global Vars */
:root {
/** The Colors are in RGB.
** To use them you need to put them into an rgb()
** or rgba() property.
**
** Examples:
** rgb( var( --color-primary ) );
** rgba( var( --color-primary ), .5 );
*/
<?php
	foreach($this->get_list() as $slug => $info){
		echo '--color-' . $slug . ': ' . $info['color'] . ';' . "\n";
	}
?>
}

/* Color Classes for Gutenberg Support */
<?php
	foreach($this->get_list() as $slug => $info){
		echo '.has-' . $slug . '-background-color { background-color: rgb( var( --color-' . $slug . ' ) ); }' . "\n";
		echo '.has-' . $slug . '-color { color: rgb( var( --color-' . $slug . ' ) ); }' . "\n";
	}
?>
</style>