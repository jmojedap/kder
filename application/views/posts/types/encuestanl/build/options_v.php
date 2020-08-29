<div v-show="edition_status == 'options'">
    <form accept-charset="utf-8" method="POST" @submit.prevent="send_child_form">
        <div class="row">
            <div class="col-md-8 offset-md-4">
                <p>Agregar opción a <span class="text-primary">{{ current.title }}</span>:</p>
            </div>
        </div>
        <div class="form-group row">
            <label for="title" class="col-md-4 col-form-label text-right">Título</label>
            <div class="col-md-8">
                <input
                    name="title" type="text" class="form-control" required
                    v-model="form_values.title"
                >
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-8 offset-md-4">
                <button class="btn btn-primary w120p"> Agregar</button>
            </div>
        </div>
    </form>

    <table class="table bg-white">
        <thead>
            <th>Título</th>
            <th width="90px"></th>
        </thead>
        <tbody>
            <tr v-for="(scene, scene_key) in scenes" v-show="scene.parent_id == current.id">
                <td>{{ scene.title }}</td>
                <td>
                    <button class="a4" v-on:click="set_scene(scene.id, scene_key)">
                        <i class="fa fa-pencil-alt"></i>
                    </button>
                    <button class="a4" data-toggle="modal" data-target="#delete_modal" v-on:click="set_deleteable(scene_key)">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>