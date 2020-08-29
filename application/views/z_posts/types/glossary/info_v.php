<div class="row">
    <div class="col-md-4">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>ID</td>
                    <td><?= $row->id ?></td>
                </tr>
                <tr>
                    <td>type_id</td>
                    <td><?= $row->type_id ?></td>
                </tr>
                <tr>
                    <td>post_name</td>
                    <td><?= $row->post_name ?></td>
                </tr>
                <tr>
                    <td>status</td>
                    <td><?= $row->status ?></td>
                </tr>
                <tr>
                    <td>slug</td>
                    <td><?= $row->slug ?></td>
                </tr>
                <tr>
                    <td>image_id</td>
                    <td><?= $row->image_id ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>count comments</td>
                    <td><?= $row->count_comments ?></td>
                </tr>
                <tr>
                    <td>published at</td>
                    <td><?= $row->published_at ?></td>
                </tr>
                <tr>
                    <td>updater_id</td>
                    <td><?= $row->updater_id ?></td>
                </tr>
                <tr>
                    <td>updated_at</td>
                    <td><?= $row->updated_at ?></td>
                </tr>
                <tr>
                    <td>creator_id</td>
                    <td><?= $row->creator_id ?></td>
                </tr>
                <tr>
                    <td>created_at</td>
                    <td><?= $row->created_at ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2><?= $row->post_name ?></h2>
                <div>
                    <h4 class="text-muted">content</h4>
                    <?= $row->excerpt ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">content</h4>
                    <?= $row->content ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">content json</h4>
                    <?= $row->content_json ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">keywords:</h4>
                    <?= $row->keywords ?>
                </div>
            </div>
        </div>
    </div>
</div>