<table class="table bg-white">
    <thead>
        <th width="20px">
            <div class="custom-control custom-checkbox">
                <input
                    type="checkbox"
                    id="select_all"
                    class="custom-control-input"
                    v-model="all_selected"
                    v-on:click="select_all"
                    >
                <label class="custom-control-label" for="select_all">
                    <span class="text-hide">-</span>
                </label>
            </div>
        </th>
        <th width="50px">ID</th>
        <th width="">ID Comentario</th>
        <th width="">Texto</th>
        <th width="50px"></th>
    </thead>
    <tbody>
        <tr v-for="(comment, key) in list">
            <td>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" v-bind:id="`row_${comment.id}`" v-model="selected" v-bind:value="comment.id">
                    <label class="custom-control-label" v-bind:for="`row_${comment.id}`">
                        <span class="text-hide">-</span>
                    </label>
                </div>
            </td>
            
            <td>
                {{ comment.id }}
            </td>
            <td>
                <a href="#" v-bind:onclick="`load_cf('comments/read/${comment.id}')`">
                    {{ comment.id }}
                </a>
            </td>
            <td>{{ comment.comment_text }}</td>
            <td>
                <button class="btn btn-danger" v-on:click="delete_row(key,comment.id,comment.document_id)">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
            
        </tr>
    </tbody>
</table>