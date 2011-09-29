<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of grades_model
 *
 * @author U7
 */
class grades_model extends MY_Model {
    private $table_name = 'grades';
    
    public function save_grades($date, $data)
    {
        // Формат $data:
        // $data[subjects] = array(1=>История, 2=>Информатика...);
        // $data[grades][studentkey][subjectkey] = grade
        // $data[comments][studentkey][subjectkey] = comment
        //
        // Надо переформатировать в:
        // $batch_data = array(
        // array(
        // user_profile_id
        // grade
        // comment
        // subject
        // num
        // date
        // ),
        // ...
        // );
        $ci = & get_instance();
        $max_lessons = $ci->config->item('max_lessons');
        $subjects = $data['subjects'];
        $grades   = $data['grades'];
        $comments = $data['comments'];
        
        $batch_data = array();
        foreach($studens as $user_profile_id => $student)
        {
            for($num = 1; ($num - 1) < $max_lessons; ++$num)
            {
                $batch_row = array();
                if(isset($grades[$user_profile_id][$num]) && trim($grades[$user_profile_id][$num]) != '')
                    $batch_row['grade'] = $grades[$user_profile_id][$num];
                if(isset($comments[$user_profile_id][$num]) && trim($comments[$user_profile_id][$num]) != '')
                    $batch_row['comment'] = $comments[$user_profile_id][$num];
                
                if(sizeof($batch_row) > 0)
                {
                    $batch_row['user_profile_id'] = $user_profile_id;
                    $batch_row['subject'] = $subjects[$num];
                    $batch_row['num'] = $num;
                    $batch_row['date'] = $date;   
                    
                    $batch_data[] = $batch_row;
                }
            }
        }
        
        // сохранение информации
        $this->db->trans_start();
        //@TODO: решить как действовать со старыми датами жарнала, наверное стирать?
        if(sizeof($batch_data) > 0)
            $this->db->insert_batch ($this->table_name, $batch_data);
        $this->db->trans_complete();
        
        return TRUE;
    }
}

?>
