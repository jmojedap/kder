<div v-show="edition_status == 'image'">
    <div v-show="current.image_id > 0">
        <div class="mb-2">
            <button class="btn btn-warning" @click="delete_image()">
                <i class="fa fa-trash"></i>
            </button>
        </div>
        <img
            v-bind:src="current.url_image"
            class="rounded w100pc"
            alt="scene image"
            onerror="this.src='<?= URL_IMG ?>app/nd.png'"
        >
    </div>
    <div v-show="current.image_id == 0">
        <?php $this->load->view('common/upload_file_form_v') ?>
    </div>
</div>