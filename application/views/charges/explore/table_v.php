<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px"><input type="checkbox" @change="select_all" v-model="all_selected"></th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Valor</th>
            <th>Descripción</th>
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td><input type="checkbox" v-model="selected" v-bind:value="element.id"></td>
                
                <td>
                    <a v-bind:href="`<?= base_url("charges/info/") ?>` + element.id">
                        {{ element.title }}
                    </a>
                </td>

                <td>
                    {{ element.charge_type_id | type_name }}
                </td>

                <td>{{ element.charge_value | currency }}</td>

                <td>{{ element.excerpt }}</td>
                
                <td>
                    <button class="a4" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>