<div class="table-responsive">
    <table class="table bg-white">
        <thead>
            <th width="10px"><input type="checkbox" @change="select_all" v-model="all_selected"></th>
            <th>Cobro</th>
            <th>Estudiante</th>
            <th>Valor</th>
            <th>Pagado</th>
            <th width="50px"></th>
        </thead>
        <tbody>
            <tr v-for="(element, key) in list" v-bind:id="`row_` + element.id">
                <td><input type="checkbox" v-model="selected" v-bind:value="element.id"></td>
                <td >{{ element.title }}</td>
                <td>{{ element.student_name }}</td>
                <td class="text-right">
                    {{ element.charge_value | currency }}
                </td>
                <td>
                    <div class="dropdown">
                        <a 
                            class="btn dropdown-toggle w50p btn-light"
                            href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            v-bind:class="{'btn-success': element.status == 1 }"
                            >
                            <span v-show="element.status == 1">Sí</span>
                            <span v-show="element.status == 0">No</span>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a
                                class="dropdown-item"
                                href="#"
                                @click="set_payed(key, 1)"
                                >
                                Sí
                            </a>
                            <a
                                class="dropdown-item"
                                href="#"
                                @click="set_payed(key, 0)"
                                >
                                No
                            </a>
                        </div>
                    </div>
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

<!-- Modal -->
<div class="modal fade" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="detail_modal_label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detail_modal_label">{{ element.title }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-borderless table-sm">
            <tr>
                <td>ID Pago</td>
                <td>{{ element.id }}</td>
            </tr>
            <tr>
                <td>Cobro</td>
                <td>{{ element.title }}</td>
            </tr>
            <tr>
                <td>Estudiante</td>
                <td>{{ element.student_name }}</td>
            </tr>
            <tr>
                <td>Tipo cobro</td>
                <td>{{ element.charge_type_id | type_name }}</td>
            </tr>
            <tr>
                <td>Valor</td>
                <td>{{ element.charge_value | currency }}</td>
            </tr>
            <tr>
                <td>Editado</td>
                <td>{{ element.updated_at }}</td>
            </tr>
            <tr>
                <td>Creado</td>
                <td>{{ element.created_at }}</td>
            </tr>
        </table>
        <p>
            {{ element.notes }}
        </p>
      </div>
      <div class="modal-footer">
            <a class="btn btn-primary w100p" v-bind:href="`<?= base_url('payments/edit/') ?>` + element.id">Abrir</a>
            <button type="button" class="btn btn-secondary w100p" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>