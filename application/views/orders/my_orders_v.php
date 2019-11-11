<table class="table bg-white">
    <thead>
        <th>Código</th>
        <th>Fecha</th>
        <th>Valor</th>
        <th>Estado</th>
    </thead>
    <tbody>
        <?php foreach ( $orders->result() as $row_order ) { ?>
            <tr>
                <td><?php echo $row_order->order_code ?></td>
                <td><?php echo $this->pml->date_format($row_order->edited_at); ?></td>
                <td><?php echo $this->pml->money($row_order->amount) ?></td>
                <td><?php echo $this->Item_model->name(7, $row_order->status); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>