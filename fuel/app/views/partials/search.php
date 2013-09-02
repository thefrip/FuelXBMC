<li class="divider-vertical"></li>
<li>
	<?php
		$action = Uri::base(false);
		if (Uri::segment(1))
    {
      // Nom du controller appelé est converti au singulier puis au pluriel
      // Exemple person => person => people mais aussi people => person => people
      $action .= Inflector::pluralize(Inflector::singularize(Uri::segment(1))).'/';
    }
		$action .= 'pre_search';
	?>
	<form class="navbar-search pull-left" action="<?php echo $action; ?>" method="post">
		<input type="text" class="search-query" name="query"  placeholder="<?php echo Lang::get('global.action_search'); ?>" />
	</form>
</li>
