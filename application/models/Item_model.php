<?php

class Item_model extends CI_Model{
    
    function __construct(){
        parent::__construct();
        
    }
    
// CRUD ITEM
//---------------------------------------------------------------------------------------------------------
    
    /**
     * Devuelve objeto query con resultados de búsqueda
     * 
     * @param type $filters
     * @param type $per_page
     * @param type $offset
     * @return type
     */
    function search($filters, $per_page = NULL, $offset = NULL)
    {
        
        $this->load->model('Search_model');
        
        //Construir búsqueda
        //Crear array con términos de búsqueda
            if ( strlen($filters['q']) > 2 ){
                $palabras = $this->Searh_model->palabras($filters['q']);
                $concat_fields = $this->Search_model->concat_fields(array('item_name', 'long_name', 'abbreviation', 'slug'));

                foreach ($palabras as $palabra) {
                    $this->db->like("CONCAT({$concat_fields})", $palabra);
                }
            }
        
        //Otros filtros
            if ( $filters['cat'] != '' ) { $this->db->where('category_id', $filters['cat']); }    //Categoría
            
        //Especificaciones de consulta
            $this->db->order_by('category_id, cod', 'ASC');
            
        //Obtener resultados
        if ( is_null($per_page) ){
            $query = $this->db->get('item'); //Resultados totales
        } else {
            $query = $this->db->get('item', $per_page, $offset); //Resultados por página
        }
        
        return $query;
        
    }
    
    
    
    function next_cod($category_id)
    {
        $cod = 1;
        
        $this->db->select('MAX(cod) AS max_cod');
        $this->db->where('category_id', $category_id);
        $query = $this->db->get('item');
        
        if ( $query->num_rows() > 0 ) 
        {
            $cod = $query->row()->max_cod + 1;
        }
        
        return $cod;
    }
    
    function delete($conditiones)
    {
        $this->db->where($conditiones);
        $this->db->delete('item');
    }
    
    /**
     * Guardar un registro en la tabla item. Insertar o Editar.
     * @param type $arr_row
     * @return type
     */
    function save($arr_row, $item_id)
    {
        //Set condition
            $condition = "id = {$item_id}";
            if ( $item_id == 0 ) { $condition = "category_id = {$arr_row['category_id']} AND cod = {$arr_row['cod']}"; }
        
        //Insert or Update
            $response['row_id'] = $this->Db_model->save('item', $condition, $arr_row);
            
        //Result
            $response['result'] = 0;
                if ( $response['row_id'] > 0 ) { $response['result'] = 1; }
        
        return $response;
    }
    
    /**
     * Devuelve el value del field item.cod para una categoría
     * dado un value de un field
     * 
     * @param type $category_id
     * @param type $value
     * @param type $field
     * @return type
     */
    function cod($category_id, $value, $field = 'abbreviation')
    {   
        $condition = "category_id = {$category_id} AND {$field} = '{$value}'";
        $cod = $this->Pcrn->field('item', $condition, 'cod');
        
        return $cod;
    }
    
// DATOS
//-----------------------------------------------------------------------------
    
    function items($category_id)
    {
        $this->db->order_by('cod', 'ASC');
        $items = $this->db->get_where('item', "category_id = {$category_id}");
        
        return $items;
    }
    
    /**
     * Devuelve el name de un item con el formato correspondiente.
     * 
     * @param type $category_id
     * @param type $cod
     * @param type $field
     * @return type
     */
    function name($category_id, $cod, $field = 'item_name')
    {
        $name = 'ND';
        
        $this->db->select("{$field} as field");
        $this->db->where('cod', $cod);
        $this->db->where('category_id', $category_id);
        $query = $this->db->get('item');
        
        if ( $query->num_rows() > 0 ) 
        {
            $name = $query->row()->field;
        }
        
        return $name;
    }
    
    /**
     * Devuelve el name de un item con el formato correspondiente, a partir
     * del item.id
     * 
     * @param type $item_id
     * @return type
     */
    function name_id($item_id, $field = 'item')
    {
        $name = 'ND';
        
        $this->db->select("{$field} as field");
        $this->db->where('id', $item_id);
        $query = $this->db->get('item');
        
        if ( $query->num_rows() > 0 ) 
        {
            $name = $query->row()->field;
        }
        
        return $name;
    }
    
    
// Arrays
//-----------------------------------------------------------------------------
    
    /**
     * Devuelve un array con índice y value para una categoría específica de items
     * Dadas unas características definidas en el array $config
     * 
     * @param type $condition
     * @return type
     */
    function arr_cod($condition)
    {   
        $this->db->select('cod, item_name');
        $this->db->where($condition);
        $this->db->order_by('position', 'ASC');
        $this->db->order_by('cod', 'ASC');
        $query = $this->db->get('item');
        
        $arr_item = $this->pml->query_to_array($query, 'item_name', 'cod');
        
        return $arr_item;
    }
    
    /**
     * Array con options de item, para elementos select de formularios.
     * La variable $condition es una condición WHERE de SQL para filtrar los items.
     * En el array el índice corresponde al cod y el value del array al
     * field item. La variable $empty_value se pone al principio del array
     * cuando el field select está vacío, sin ninguna opción seleccionada.
     * 
     * @param type $condition
     * @param type $empty_value
     * @return type
     */
    function options($condition, $empty_value = NULL)
    {
        
        $select = 'CONCAT("0", (cod)) AS str_cod, item_name AS field_value';
        
        $this->db->select($select);
        $this->db->where($condition);
        $this->db->order_by('cod', 'ASC');
        $this->db->order_by('position', 'ASC');
        $query = $this->db->get('item');
        
        $options_pre = $this->pml->query_to_array($query, 'field_value', 'str_cod');
        
        if ( ! is_null($empty_value) ) 
        {
            $options = array_merge(array('' => '[ ' . $empty_value . ' ]'), $options_pre);
        } else {
            $options = $options_pre;
        }
        
        return $options;
    }
    
    /**
     * Array con options de item, para elementos select de formularios.
     * La variable $condition es una condición WHERE de SQL para filtrar los items.
     * En el array el índice corresponde al id y el value del array al
     * field item. La variable $empty_value se pone al principio del array
     * cuando el field select está vacío, sin ninguna opción seleccionada.
     * 
     * @param type $condition
     * @param type $empty_value
     * @return type
     */
    function options_id($condition, $empty_value = NULL)
    {
        $select = 'CONCAT("0", (id)) AS field_index_str, item_name AS field_value';
        
        $this->db->select($select);
        $this->db->where($condition);
        $this->db->order_by('position', 'ASC');
        $this->db->order_by('cod', 'ASC');
        $query = $this->db->get('item');
        
        $options_pre = $this->pml->query_to_array($query, 'field_value', 'field_index_str');
        
        if ( ! is_null($empty_value) ) {
            $options = array_merge(array('' => '[ ' . $empty_value . ' ]'), $options_pre);
        } else {
            $options = $options_pre;
        }
        
        return $options;
    }
    
    /**
     * Devuelve array con valuees predeterminados para utilizar en la función
     * Item_model->arr_item
     * 
     * @param type $format
     * @return string
     */
    function arr_config_item($format = 'cod')
    {
        $arr_config['order_type'] = 'ASC';
        $arr_config['field_value'] = 'item_name';
        
        switch ($format) 
        {
            case 'id':
                //id, ordenado alfabéticamente
                $arr_config['field_index'] = 'id';
                $arr_config['order_by'] = 'item_name';
                $arr_config['str'] = TRUE;
                break;
            case 'cod':
                //cod, ordenado por cod
                $arr_config['field_index'] = 'cod';
                $arr_config['order_by'] = 'cod';
                $arr_config['str'] = TRUE;
                break;
            case 'cod_num':
                //cod, ordenado por cod, numérico
                $arr_config['field_index'] = 'cod';
                $arr_config['order_by'] = 'cod';
                $arr_config['str'] = FALSE;
                break;
            case 'cod_abr':
                //cod, abreviatura, string
                $arr_config['field_index'] = 'cod';
                $arr_config['field_value'] = 'abbreviation';
                $arr_config['order_by'] = 'abbreviation';
                $arr_config['str'] = TRUE;
                break;
        }
        
        return $arr_config;
    }
    
    /**
     * Devuelve un array con índice y value para una categoría específica de items
     * Dadas unas características definidas en el array $config
     * 
     * @param type $format
     * @return type
     */
    function arr_item($condition, $format = 'cod')
    {
        
        $config = $this->arr_config_item($format);
        
        $select = $config['field_index'] . ' AS field_index, CONCAT("0", (' . $config['field_index'] . ')) AS field_index_str, ' . $config['field_value'] .' AS field_value';
        
        $indice = 'field_index_str';
        if ( ! $config['str'] ) { $indice = 'field_index'; }
        
        $this->db->select($select);
        $this->db->where($condition);
        $this->db->order_by($config['order_by'], $config['order_type']);
        $query = $this->db->get('item');
        
        $arr_item = $this->pml->query_to_array($query, 'field_value', $indice);
        
        return $arr_item;
    }
    
    function arr_field($category_id, $field)
    {
        $config = $this->arr_config_item($format);
        
        $select = $config['field_index'] . ' AS field_index, CONCAT("0", (cod)) AS field_index_str, ' . $field .' AS field_value';
        
        $indice = 'field_index_str';
        if ( ! $config['str'] ) { $indice = 'field_index'; }
        
        $this->db->select($select);
        if ( $category_id > 0 ) { $this->db->where('category_id', $category_id); }
        $this->db->where($config['condition']);
        $this->db->order_by($config['order_by'], $config['order_type']);
        $query = $this->db->get('item');
        
        $arr_item = $this->pml->query_to_array($query, 'field_value', $indice);
        
        return $arr_item;
    }
}