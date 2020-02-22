<div class="row">
    <div class="col-md-4">
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>ID</td>
                    <td><?php echo $row->id ?></td>
                </tr>
                <tr>
                    <td>type_id</td>
                    <td><?php echo $row->type_id ?></td>
                </tr>
                <tr>
                    <td>post_name</td>
                    <td><?php echo $row->post_name ?></td>
                </tr>
                <tr>
                    <td>status</td>
                    <td><?php echo $row->status ?></td>
                </tr>
                <tr>
                    <td>slug</td>
                    <td><?php echo $row->slug ?></td>
                </tr>
                <tr>
                    <td>image_id</td>
                    <td><?php echo $row->image_id ?></td>
                </tr>
            </tbody>
        </table>

        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>count comments</td>
                    <td><?php echo $row->count_comments ?></td>
                </tr>
                <tr>
                    <td>published at</td>
                    <td><?php echo $row->published_at ?></td>
                </tr>
                <tr>
                    <td>editor_id</td>
                    <td><?php echo $row->editor_id ?></td>
                </tr>
                <tr>
                    <td>edited_at</td>
                    <td><?php echo $row->edited_at ?></td>
                </tr>
                <tr>
                    <td>creator_id</td>
                    <td><?php echo $row->creator_id ?></td>
                </tr>
                <tr>
                    <td>created_at</td>
                    <td><?php echo $row->created_at ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2><?php echo $row->post_name ?></h2>
                <div>
                    <h4 class="text-muted">content</h4>
                    <?php echo $row->excerpt ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">content</h4>
                    <?php echo $row->content ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">content json</h4>
                    <?php echo $row->content_json ?>
                </div>
                <hr>
                <div>
                    <h4 class="text-muted">keywords:</h4>
                    <?php echo $row->keywords ?>
                </div>
            </div>
        </div>
    </div>
</div>