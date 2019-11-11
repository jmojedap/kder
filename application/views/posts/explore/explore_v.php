<script src="<?php echo URL_RESOURCES . 'js/pcrn.js' ?>"></script>

<?php $this->load->view($views_folder . 'script_js'); ?>

<div class="row">
    <div class="col-md-6">
        <?php $this->load->view($views_folder . 'search_form_v'); ?>
    </div>

    <div class="col-md-3">
        <a class="btn btn-warning text-light"
            id="btn_delete_selected"
            title="Eliminar los registros seleccionados"
            data-toggle="modal"
            data-target="#modal_delete"
            >
            <i class="fa fa-trash"></i>
        </a>
        
        <div class="btn-group d-none" role="group">
            <a href="<?php echo base_url("{$controller}/export/?{$str_filters}") ?>" class="btn btn-success" title="Exportar registros encontrados a Excel">
                <i class="fa fa-file-excel"></i> Exportar
            </a>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="pull-right">
            <?php $this->load->view('common/ajax_pagination_v'); ?>
        </div>
    </div>
</div>

<div id="elements_table">
    <?php $this->load->view($views_folder . 'table_v'); ?>
</div>

<?php $this->load->view('common/modal_delete_v'); ?>