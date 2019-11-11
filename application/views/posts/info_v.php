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
            </tbody>
        </table>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div>
                    <h3>excerpt</h3>
                    <?php echo $row->excerpt ?>
                </div>
                <div>
                    <h3>content</h3>
                    <?php echo $row->content ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <table class="table bg-white">
            <tbody>
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
</div>