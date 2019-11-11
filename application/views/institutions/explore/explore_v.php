<script src="<?php echo URL_RESOURCES . 'js/pcrn.js' ?>"></script>

<?php $this->load->view($views_folder . 'script_js'); ?>

<div class="row">
    <div class="col-md-6 d-none d-md-table-cell d-lg-table-cell">
        <?php $this->load->view($views_folder . 'search_form_v'); ?>
    </div>

    <div class="col">
        <a class="btn btn-light"
            id="btn_delete_selected"
            title="Eliminar las instituciones seleccionadas"
            data-toggle="modal"
            data-target="#modal_delete"
            >
            <i class="fa fa-trash"></i>
        </a>
        
        <div class="btn-group" role="group">
            <a href="<?php echo base_url("users/export/?{$str_filters}") ?>" class="btn btn-light" title="Exportar registros encontrados a Excel">
                <i class="fa fa-file-excel"></i> Exportar
            </a>
        </div>
    </div>
    
    <div class="col mb-2">
        <?php $this->load->view('common/ajax_pagination_v'); ?>
    </div>
</div>

<div id="elements_table" class="">
    <?php $this->load->view($views_folder . 'table_v'); ?>
</div>

<?php $this->load->view('common/modal_delete_v'); ?>