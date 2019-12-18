<table class="table bg-white">
    <thead>
        <th width="20px">
            <input type="checkbox" @click="select_all" v-model="all_selected">
        </th>
        <th>Pago</th>
        <th>Estudiante</th>
        <th>Estado</th>
    </thead>
    <tbody>
        <tr v-for="(payment, key) in list" v-bind:id="`row_` + payment.id">
            <td>
                <input type="checkbox" v-model="selected" v-bind:value="payment.id">
            </td>
            <td>{{ payment.title }}</td>
            <td>{{ payment.student_name }}</td>
            <td>
                <div class="dropdown">
                    <a 
                        class="btn dropdown-toggle w100p btn-light"
                        href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        v-bind:class="{'btn-success': payment.status == 1 }"
                        >
                        <span v-show="payment.status == 1">Pagado</span>
                        <span v-show="payment.status == 0">Sin pagar</span>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <a
                            class="dropdown-item"
                            href="#"
                            @click="set_payed(key, 1)"
                            >
                            Pagado
                        </a>
                        <a
                            class="dropdown-item"
                            href="#"
                            @click="set_payed(key, 0)"
                            >
                            Sin pagar
                        </a>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>