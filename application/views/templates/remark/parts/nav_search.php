<?php 
    $input_value = '';
    if ( isset($search['q']) ) { $input_value = $search['q']; }
    $search_controller = $this->uri->segment(1);
?>
<!-- Site Navbar Seach -->
<div class="collapse navbar-search-overlap" id="site-navbar-search">
    <form role="search" action="<?= base_url("app/search/{$search_controller}/explore") ?>">
        <div class="form-group">
            <div class="input-search">
                <i class="input-search-icon wb-search" aria-hidden="true"></i>
                <input id="campo-q_nav_buscar" type="text" class="form-control" name="q" placeholder="Buscar..." value="<?= $input_value ?>">
                <button type="button" class="input-search-close icon wb-close" data-target="#site-navbar-search"
                    data-toggle="collapse" aria-label="Close">
                </button>
            </div>
        </div>
    </form>
</div>