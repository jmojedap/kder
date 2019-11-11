<table class="table bg-white">
    <thead>
        <th>ID</th>
        <th>Code</th>
        <th>Nombre comprador</th>
        <th>Usuario</th>
        <th>Valor</th>
        <th>Estado</th>
        <th>Editado</th>
    </thead>
    <tbody>
        <?php foreach ( $orders->result() as $row_order ) { ?>
            <?php
                $cl_status = ( $row_order->status == 1 ) ? 'table-success' : '' ;
            ?>
            <tr>
                <td>
                    <?php echo $row_order->id; ?>
                </td>
                <td>
                    <?php echo $row_order->order_code; ?>
                </td>
                <td>
                    <?php echo $row_order->buyer_name ?>
                </td>
                <td>
                    <?php echo $this->App_model->name_user($row_order->user_id, 'u'); ?>
                </td>
                <td class="text-right">
                    <?php echo $this->pml->money($row_order->amount); ?>
                </td>
                <td class="<?php echo $cl_status ?>">
                    <?php echo $this->Item_model->name(7, $row_order->status); ?>
                </td>
                <td>
                    <span title="<?php echo $row_order->edited_at ?>">
                        <?php echo $this->pml->ago($row_order->edited_at) ?>
                    </span>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>