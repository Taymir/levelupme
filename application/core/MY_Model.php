<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MY_Model
 *
 * @author u7
 */
class MY_Model extends CI_Model {
    protected function typical_find($table_name, $id)
    {
        $query = $this->find($table_name, $id);

        if ($query->num_rows() == 1) return $query->row();
            return NULL;
    }

    private function find($table_name, $id)
    {
        $this->db->select('*');

        $this->db->from($table_name);
        $this->db->where('id', $id);

        return $this->db->get();
    }
    protected function typical_find_obj($table_name, $id)
    {
        $query = $this->find($table_name, $id);

        if ($query->num_rows() == 1) return $query->row();
            return NULL;
    }
    
    protected function typical_find_arr($table_name, $id)
    {
        $query = $this->find($table_name, $id);

        if ($query->num_rows() == 1) return $query->row_array();
            return NULL;
    }

    protected function typical_select($table_name, $order_by = NULL)
    {
        $this->db->select('*');

        $this->db->from($table_name);
        if($order_by !== NULL)
            $this->db->order_by($table_name . '.' .  $order_by);

        $query = $this->db->get();

        return $query->result();
    }

    protected function typical_insert($table_name, $data)
    {
        return $this->db->insert($table_name, $data);
    }

    protected function typical_update($table_name, $data, $id_value, $id_field = 'id')
    {
        $this->db->where($id_field, $id_value);
        return $this->db->update($table_name, $data);
    }

    protected function typical_delete($table_name, $id_value, $id_field = 'id')
    {
        $this->db->where($id_field, $id_value);
        $this->db->delete($table_name);

        return TRUE;
    }

    protected function add_created_field($data)
    {
        $data['created'] = date('Y-m-d H:i:s');

        return $data;
    }

    /* переформатирует массив
     * из: key | field
     *  0)  0 | Foo
     *  1) 22 | Bar
     * в список:
     *  0) Foo
     * 22) Bar
     */
    protected function Arr2List($arr, $key_or_value_field, $value_field = null) {
        $res = array();

        if($value_field === null)
        {
            $value_field = $key_or_value_field;

            foreach($arr as $key=>$row)
                $res[$key] = $row[$value_field];
        } else {
            $key_field = $key_or_value_field;

            foreach($arr as $row)
                $res[$row[$key_field]] = $row[$value_field];
        }

        return $res;
    }
}
