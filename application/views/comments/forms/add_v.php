<div id="add_comment">
    <form id="add_form" accept-charset="utf-8" @submit.prevent="send_form">
        <input type="hidden" name="document_id" value="1">
        <input type="hidden" name="segment_id" value="39">
        <input type="hidden" name="parent_id" value="0">
        <div class="form-group row">
            <div class="offset-4 col-md-8">
                <button class="btn btn-success btn-block" type="submit">
                    Crear
                </button>
            </div>
        </div>
        
        <div class="form-group row">
                <label for="comment_text" class="col-md-4 control-label">Texto comentario</label>
                <div class="col-md-8">
                    <textarea
                        id="field-comment_text"
                        name="comment_text"
                        rows="6"
                        class="form-control"
                        placeholder="Texto del comentario"
                        title="Nombres del comentario"
                        autofocus
                        v-model="form_values.comment_text"
                        ></textarea>
                </div>
            </div>
    </form>
    
    <!-- Modal -->
    <div class="modal fade" id="modal_created" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="comment">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Documento creado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <i class="fa fa-check"></i>
                    Comentario creado correctamente
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" v-on:click="goto_created">
                        Abrir comentario
                    </button>
                    <button type="button" class="btn btn-secondary" v-on:click="clean_form" data-dismiss="modal">
                        <i class="fa fa-plus"></i>
                        Crear otro
                    </button>
                </div>
            </div>
        </div>
    </div>
    
</div>

<?php
$this->load->view('comments/forms/add_vue_v');