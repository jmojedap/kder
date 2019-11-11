<?php //$this->load->view('assets/toastr'); ?>
<?php //$this->load->view('assets/chosen_jquery'); ?>

<?php 
    $options_categories = array_merge(array('' => "(Seleccione la categoría)"), $arr_categories);
?>

<div id="items_list">
    <div class="row">
        <div class="col col-md-6">

            <div class="card">
                <div class="card-body">
                    <?php echo form_dropdown('cat', $arr_categories, $category_id, 'class="form-control" id="cat" v-model="category_id" v-on:change="get_list"') ?>
                </div>

                <pre class="d-none">
                    {{ $data | json }}
                </pre>

                <table class="table table-condensed bg-white">
                    <thead>
                        <th width="50px">ID</th>
                        <th width="50px">Cód.</th>
                        <th>Nombre ítem</th>
                        <th width="91px"></th>
                    </thead>
                    <tbody>
                        <tr v-for="(row, key) in list" v-bind:class="{'table-info':row_id == row.id}">
                            <td>{{ row.id }}</td>
                            <td>{{ row.cod }}</td>
                            <td>{{ row.item_name }}</td>
                            <td>
                                <button class="btn btn-light btn-sm" v-on:click="set_form(key)">
                                    <i class="fa fa-pencil-alt"></i>
                                </button>
                                <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#delete_modal" v-on:click="current_element(key)">
                                    <i class="fa fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        <div class="col col-md-6">
            <div class="">
                <button class="btn btn-primary" v-on:click="clean_form">
                    <i class="fa fa-plus"></i>
                    Nuevo
                </button> 
                <button type="button" class="btn btn-default" v-on:click="autocomplete">
                    Autocompletar
                </button>
            </div>

            <br/>

            <div class="card">
                <div class="card-header">
                    {{ config_form.title }}
                </div>
                <div class="card-body">
                    <?php $this->load->view('system/items/manage_form_v'); ?>
                </div>
            </div>

        </div>
    </div>
    <?php $this->load->view('common/modal_simple_delete_v'); ?>
</div>

<?php $this->load->view('system/items/manage_vue_v'); ?>