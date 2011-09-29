<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pages_model
 *
 * @author U7
 */
class pages_model extends MY_Model {
    public $table_name = 'pages';
    
    public function get_page($id)
    {
        return $this->typical_find_obj($this->table_name, $id);
    }
    
    public function get_pages()
    {
        return $this->typical_select($this->table_name);
    }
    
    public function create_page($data)
    {
        return $this->typical_insert($this->table_name, $data);
    }
    
    public function save_page($id, $data)
    {
        return $this->typical_update($this->table_name, $data, $id);
    }
    
    public function delete_page($id)
    {
        return $this->typical_delete($this->table_name, $id);
    }
    
    public function link_exists($link, $except_id = null)
    {
        $this->db->select('*');
        $this->db->from($this->table_name);
        $this->db->where('link', $link);
        if($except_id !== null)
            $this->db->where('id <>', $except_id);
        $query = $this->db->get();
        
        if($query->num_rows() > 0) return true;
        return false;
    }
    
    /* OBSOLETE
    private function get_title_from_text($string, $limit = 100, $break=".", $pad="...")
    {
        $string = strip_tags(html_entity_decode($string));
        
        // return with no change if string is shorter than $limit
        if(strlen($string) <= $limit)
            return $string;

        // is $break present between $limit and the end of the string?
        if(false !== ($breakpoint = strpos($string, $break, $limit))) {
            if($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $pad;
            }
        }

        return $string;    
    }*/
}
