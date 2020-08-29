<div class="container">
    <table class="table bg-white">
        <thead>
            <th>Campo</th>
            <th>Valor</th>
        </thead>

        <?php foreach ( $fields as $field ) { ?>
            <tr>
                <td><?= $field ?></td>
                <td><?= $row->$field ?></td>
            </tr>
        <?php } ?>
    </table>
</div>