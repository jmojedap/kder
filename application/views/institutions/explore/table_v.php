<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px"><input type="checkbox" @change="select_all" v-model="all_selected"></th>
            <th>Nombre</th>
            <th>Propietario</th>
            <th>Ciudad</th>
            
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td><input type="checkbox" v-bind:id="`check_` + element.id" v-model="selected" v-bind:value="element.id"></td>
                
                <td>
                    <a v-bind:href="`<?= base_url("institutions/info/") ?>` + element.id">
                        {{ element.name }}
                    </a>
                </td>
                <td>
                    <a v-bind:href="`<?= base_url("users/profile/") ?>` + element.owner_id">
                        {{ element.owner_name }}
                    </a>
                </td>

                <td>
                    {{ element.place_name }}
                </td>
                
                <td>
                    <button class="a4" data-toggle="modal" data-target="#detail_modal" @click="set_current(key)">
                        <i class="fa fa-info"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>