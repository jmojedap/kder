<?php
class Statistic_model extends CI_Model{
    
    function girls()
    {
        $this->db->select('COUNT(event.id) AS count_visits, element_id as girl_id, image_id, src_image, src_thumbnail');
        $this->db->where('type_id', 52);
        $this->db->where('event.created_at >=', date('Y-m-d', strtotime(date('Y-m-d H:i:s'). ' - 2 days')));
        $this->db->group_by('element_id, image_id, src_image, src_thumbnail');
        $this->db->join('user', 'event.element_id = user.id');
        $this->db->order_by('COUNT(event.id)', 'desc');
        
        $girls = $this->db->get('event');

        return $girls;
    }

    function albums()
    {
        $this->db->select('COUNT(event.id) AS count_visits, element_id as album_id, image_id, title, albums.user_id AS girl_id');
        $this->db->where('type_id', 51);
        $this->db->where('event.created_at >=', date('Y-m-d', strtotime(date('Y-m-d H:i:s'). ' - 2 days')));
        $this->db->group_by('element_id, image_id, title, albums.user_id');
        $this->db->join('albums', 'event.element_id = albums.id');
        $this->db->order_by('COUNT(event.id)', 'desc');
        
        $albums = $this->db->get('event');

        return $albums;
    }

}