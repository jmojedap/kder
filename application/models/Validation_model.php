<?php
class Validation_model extends CI_Model{


// Usuarios
//-----------------------------------------------------------------------------

    /**
     * Valida que username sea único, si se incluye un ID User existente
     * lo excluye de la comparación cuando se realiza edición
     */
    function username($user_id = null)
    {
        $validation['username_unique'] = $this->Db_model->is_unique('user', 'username', $this->input->post('username'), $user_id);
        return $validation;
    }

    /**
     * Valida que username sea único, si se incluye un ID User existente
     * lo excluye de la comparación cuando se realiza edición
     * 2019-10-29
     */
    function email($user_id = null, $type = 'edition')
    {
        $validation['email_unique'] = $this->Db_model->is_unique('user', 'email', $this->input->post('email'), $user_id);
        $validation['email_gmail'] = ( substr($this->input->post('email'), -10) == '@gmail.com' );    //Debe ser correo de gmail

        $validation['email_valid'] = ( $validation['email_unique'] && $validation['email_gmail']);    //Se validan las dos condiciones

        return $validation;
    }

    /**
     * Valida que número de identificacion (id_number) sea único, si se incluye un ID User existente
     * lo excluye de la comparación cuando se realiza edición
     */
    function id_number($user_id = null)
    {
        $validation['id_number_unique'] = $this->Db_model->is_unique('user', 'id_number', $this->input->post('id_number'), $user_id);
        return $validation;
    }
}