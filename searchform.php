<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
  <div>
  	<label class="screen-reader-text" for="s">Suche nach:</label>
    <input type="text" value="" name="s" id="s" title="Suche" class="default-value" />
    <input type="submit" id="searchsubmit" value="Suchen" />
    <div id="lupe"></div>
  </div>
</form>