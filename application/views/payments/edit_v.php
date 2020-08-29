<?php $this->load->view('assets/bs4_chosen') ?>

<?php
    $options_payer = $this->App_model->options_user("institution_id = {$row_charge->institution_id} AND role = 21");  
    $options_status = $this->Item_model->options('category_id = 174');
?>

<div id="app_edit">
    <div class="card center_box_750">
        <div class="card-body">
            <form id="edit_form" accept-charset="utf-8" @submit.prevent="send_form">

                <div class="form-group row">
                    <label for="payer_id" class="col-md-4 controle-label text-right">Pagado por</label>
                    <div class="col-md-8">
                        <?= form_dropdown('payer_id', $options_payer, '', 'id="field-payer_id" class="form-control form-control-chosen" v-model="form_values.payer_id"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="status" class="col-md-4 col-form-label text-right">Estado</label>
                    <div class="col-md-8">
                        <?= form_dropdown('status', $options_status, '0', 'class="form-control" v-model="form_values.status"') ?>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="payed_value" class="col-md-4 col-form-label text-right">Valor pagado</label>
                    <div class="col-md-8">
                        <input
                            type="number"
                            id="field-payed_value"
                            name="payed_value"
                            required
                            class="form-control"
                            placeholder="Valor pagado"
                            title="Valor pagado"
                            v-model="form_values.payed_value"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="payed_at" class="col-md-4 col-form-label text-right">Pagado en</label>
                    <div class="col-md-8">
                        <input
                            type="date"
                            id="field-payed_at"
                            name="payed_at"
                            required
                            class="form-control"
                            title="titulo"
                            v-model="form_values.payed_at"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="notes" class="col-md-4 col-form-label text-right">Notas</label>
                    <div class="col-md-8">
                        <textarea
                            type="text"
                            id="field-notes"
                            name="notes"
                            class="form-control"
                            placeholder="Notas adicionales sobre el pago"
                            title="Notas adicionales sobre el pago"
                            v-model="form_values.notes"
                            ></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="offset-md-4 col-md-8">
                        <button class="btn btn-success w120p" type="submit">
                            Guardar
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    //Cargar valor en formulario
    var form_values = <?= json_encode($row) ?>;
    form_values.status = '0<?= $row->status ?>';
    form_values.payer_id = '0<?= $row->payer_id ?>';
    form_values.payed_at = '0<?= substr($row->payed_at, 0, 10) ?>';
    
    new Vue({
    el: '#app_edit',
        data: {
            form_values: form_values,
            row_id: '<?= $row->id ?>'
        },
        methods: {
            send_form: function() {
                axios.post(url_app + 'payments/save/' + this.row_id, $('#edit_form').serialize())
                    .then(response => {
                        if (response.data.status == 1)
                        {
                            toastr['success']('Guardado');
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                });
            }
        }
    });
</script>