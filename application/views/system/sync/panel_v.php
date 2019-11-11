<?php $this->load->view('system/sync/panel_js'); ?>

<?php 
    $ot = $this->input->get('ot');
    $ot_alt = $this->pml->toggle($ot, 'ASC', 'DESC');
    
    $method_name = 'Auto';
    if ( $method_id > 0 ) { $method_name = $this->Item_model->name(71, $method_id); }
?>

<div class="row mb-3">
    <div class="col-md-2">
        <button id="update_server_status" class="btn btn-primary btn-block" title="Actualizar estado de tablas locales vs. servidor">
            <i class="fa fa-retweet"></i>
            Estado
        </button>
    </div>
    <div class="col-md-10">
        <div class="dropdown">
            <button
                class="btn btn-secondary dropdown-toggle"
                type="button"
                id="dropdownMenuButton"
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
                style="width: 200px;"
                title="Seleccione el método de sincronización"
                >
                <?php echo $method_name ?>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item">Método Sincro</a>
                <a href="<?php echo base_url('sync/panel/') ?>" class="dropdown-item">Auto</a>
                <a href="<?php echo base_url('sync/panel/1') ?>" class="dropdown-item">Total</a>
                <a href="<?php echo base_url('sync/panel/2') ?>" class="dropdown-item">Nuevos ID</a>
            </div>
        </div>
    </div>
</div>

<table class="table table-hover bg-white" id="tabla_proceso">
    <thead>
        <th width="40px"></th>
        <th>
            <?php echo anchor("sync/panel/?ob=table_name&ot={$ot_alt}", 'Tabla', 'class="" title=""') ?>
        </th>
        
        <th class="d-none">
            ID L/S
        </th>
        <th class="text-center" title="Cantidad registros Local vs. Servidor">
            <?php echo anchor("sync/panel/?ob=quan_rows&ot={$ot_alt}", 'Registros L | S') ?>
        </th>
        <th title="Diferencia en el número de registros ">Dif</th>
        <th width="150px">Estado</th>
        <th title="Método de sincronización para la tabla">Método Sincro</th>
        <th>Avance</th>
        <th class="text-center" title="Tiempo estimado para ejecutar sincronización">
            <i class="fa fa-clock"></i> t estimado
        </th>
        <th>
            <a href="<?php echo base_url("sync/panel/?ob=sincro_date&ot={$ot_alt}") ?>">Sincronizada</a>
        </th>
    </thead>
    <tbody>
        <?php foreach ($tables->result() as $row_table) : ?>
            <?php
                $ago_class = '';
                $days_ago = $this->pml->interval($row_table->sincro_date, date('Y-m-d H:i:s'));
                if ( $days_ago < 3 && ! is_null($days_ago) ) { $ago_class = ''; }
                if ( $days_ago > 15 && ! is_null($days_ago) ) { $ago_class = ''; }
                
                //Diferencia
                $att_diff['value'] = $row_table->quan_rows - $row_table->quan_rows_server;
                if ( $row_table->method_id == 2 ) { $att_diff['value'] = $row_table->max_id - $row_table->max_ids; }
                
                $att_diff['class'] = '';
                if ( $att_diff['value'] < 0 ) { $att_diff['class'] = 'table-warning'; }
                if ( $att_diff['value'] > 0 ) { $att_diff['class'] = 'table-info'; }
                
                //Barra percent
                    $pct_row = 0;
                    $clase_barra = '';
                
            ?>

            <tr id="row_<?php echo $row_table->table_name ?>">
                <td>
                    <button
                        id="sincro_<?php echo $row_table->table_name ?>"
                        class="sincro btn btn-secondary btn-sm"
                        data-table="<?php echo $row_table->table_name ?>"
                        data-since_id="<?php echo $row_table->max_id ?>"
                        data-method_id="<?php echo $row_table->method_id ?>"
                        title="Sincronizar tabla"
                        >
                        <i class="fa fa-sync-alt"></i>
                    </button>
                </td>

                <td>
                    <?php echo $row_table->table_name ?>
                </td>
                
                <td class="text-right d-none">
                    <span class="text-secondary">
                        <?php echo number_format($row_table->max_id, 0, ',', '.') ?>
                    </span>
                    <br/>
                    <span class="text-secondary">
                        <?php echo number_format($row_table->max_ids, 0, ',', '.') ?>
                    </span>
                </td>
                
                <td class="text-center" title="Cantidad registros Local vs. Servidor">
                    <span>
                        <?php echo number_format($row_table->quan_rows, 0, ',', '.') ?>
                    </span>
                     | 
                    <span class="text-primary">
                        <?php echo number_format($row_table->quan_rows_server, 0, ',', '.') ?>
                    </span>
                </td>
                
                <td class="<?php echo $att_diff['class'] ?> text-right">
                    <?php echo number_format($att_diff['value'],0,',','.') ?>
                </td>

                <td id="status_<?php echo $row_table->table_name ?>" width="150px"></td>

                <td>
                    <?php echo $this->Item_model->name(71, $row_table->method_id); ?>
                </td>

                <td id="percent_<?php echo $row_table->table_name ?>">
                    <div class="progress">
                        <div
                            id="percent_bar_<?php echo $row_table->table_name ?>"
                            class="progress-bar active <?php echo $clase_barra ?>"
                            role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                            style="width: <?php echo $pct_row ?>%"
                            >
                        </div>
                    </div>
                </td>

                <td class="text-center">
                    <?php echo $this->pml->interval($row_table->start_date, $row_table->sincro_date) ?>
                </td>

                <td id="ago_<?php echo $row_table->table_name ?>" class="<?php echo $ago_class ?>" title="<?php echo $row_table->sincro_date ?>">
                    <?php echo $this->pml->ago($row_table->sincro_date) ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>         