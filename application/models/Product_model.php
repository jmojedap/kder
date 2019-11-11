<?php
class Product_model extends CI_Model{

    function basic($product_id)
    {
        $data['row'] = $this->Db_model->row_id('products', $product_id);

        return $data;
    }

    /**
     * Listado de productos para sección products/pricing
     */
    function products()
    {
        $this->db->order_by('price', 'asc');
        $products = $this->db->get('products');

        return $products;
    }

    function row($product_id)
    {
        $row = $this->Db_model->row_id('products', $product_id);

        return $row;
    }
}