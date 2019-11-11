<div id="explore_app">
    <div class="row">
        <div class="col col-md-6">
            <?php $this->load->view("{$views_folder}search_form_v"); ?>
        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal_delete" v-if="selected">
                <span title="Eliminar registros seleccionados" data-toggle="tooltip">
                    <i class="fa fa-trash"></i>
                </span>
            </button>
            <a href="<?php echo base_url('comments/export') ?>" class="btn btn-light" title="Descargar en Excel">
                <i class="fa fa-download"></i>
            </a>
        </div>
        
        <div class="col col-md-3">
            <?php $this->load->view('common/vue_pagination_v'); ?>
        </div>
    </div>
    
    <?php $this->load->view("{$views_folder}table_v"); ?>
    
    <?php $this->load->view('common/modal_delete_v'); ?>
</div>

<?php
$this->load->view("{$views_folder}vue_v");