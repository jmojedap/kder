<div id="add_file" style="">
    <div class="card">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="file_form" @submit.prevent="send_form">
                <div class="form-group row">
                    <label for="file_field" class="col-md-3 col-form-label ">Archivo</label>
                    <div class="col-md-9">
                        <input
                            type="file"
                            name="file_field"
                            required
                            class="form-control"
                            placeholder="Archivo"
                            title="Arcivo a cargar"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-9 offset-md-3">
                        <button class="btn btn-success btn-block" type="submit">
                            Cargar
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>