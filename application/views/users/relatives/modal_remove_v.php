<div class="modal" tabindex="-1" role="dialog" id="remove_modal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Remover familiar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>El familiar será desasignado del estudiante pero no se eliminará de la plataforma</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" v-on:click="remove_relative" data-dismiss="modal">
                    Remover
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>