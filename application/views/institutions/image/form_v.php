<div id="add_file">
    <div class="card">
        <div class="card-body">
            <form accept-charset="utf-8" method="POST" id="file_form" @submit.prevent="send_form">
                <div class="form-group row">
                    <label for="file_field" class="col-md-3 col-form-label text-right">Archivo</label>
                    <div class="col-md-9">
                        <input
                            type="file"
                            name="file_field"
                            required
                            class="form-control"
                            placeholder="Archivo"
                            title="Archivo a cargar"
                            >
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-9 offset-md-3">
                        <button class="btn btn-success w120p" type="submit">
                            Cargar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>