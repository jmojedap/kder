<div id="acl">
    <div class="row">
        <div class="col-md-2">
            <select
                class="form-control"
                title="Subdominio de la aplicación"
                v-model="subdomain"
                v-on:change="change_subdomain"
                >
                <option v-for="subdomain in subdomains" v-bind:value="subdomain.value">{{ subdomain.name }}</option>
            </select>
        </div>
        <div class="col-md-2">
            <select
                class="form-control"
                v-model="controller"
                v-on:change="change_controller"
                >
                <option v-for="option in controllers" v-bind:value="option.controller" v-show="option.subdomain == subdomain">
                    {{ option.subdomain }} / {{ option.controller }}
                </option>
            </select>
        </div>
        <div class="col-md-8">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modal_form" v-on:click="clean_form">
                <i class="fa fa-plus"></i>
                Nuevo
            </button>
        </div>
    </div>
    
    <br/>
    
    <table class="table table-hover bg-white">
        <thead>
            <th width="30px">ID</th>
            <th>cf</th>
            <th>Roles</th>
            <th>Descripción</th>
            <th width="91px">

            </th>
        </thead>
        <tbody>
            <tr v-for="(row, key) in list" v-bind:class="{'table-primary':row_id == row.id}">
                <td>{{ row.id }}</td>
                <td>{{ row.cf }}</td>
                <td>{{ row.roles }}</td>
                <td>{{ row.description }}</td>
                <td>
                    <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#modal_form" v-on:click="load_form(key)">
                        <i class="fa fa-pencil-alt"></i>
                    </button>
                    <button class="btn btn-light btn-sm" data-toggle="modal" data-target="#modal_delete" v-on:click="current_element(key)">
                            <i class="fa fa-trash-alt"></i>
                        </button>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="modal" tabindex="-1" role="dialog" id="modal_form">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ config_form.title }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php $this->load->view('system/admin/acl/form_v'); ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php //$this->load->view('common/modal_simple_delete_v'); ?>
</div>

<?php $this->load->view('system/admin/acl/vue_v'); ?>