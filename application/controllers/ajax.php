<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ajax
 *
 * @author u7
 */
class ajax extends MY_Controller {
    public function autocomplete()
    {

        $query = $this->input->get('q');
        if($query)
        {
           $this->load->model('timetables_model');
            // LOAD DATA
            $data = $this->timetables_model->get_subjects($query);
            /*$data = array( //@DEBUG
                    'Русский язык',
                    'Литература',
                    'Иностранный язык (английский, немецкий, французский)',
                    'Математика',
                    'Информатика и ИКТ',
                    'История',
                    'Обществознание',
                    'География',
                    'Биология',
                    'Физика',
                    'Химия',
                    'Экономика',
                    'Право',
                    'Основы безопасности жизнедеятельности',
                    'Технология',
                    'Искусство (мировая художественная культура)',
                    'Физическая культура',
                    'Астрономия',
                    'Экология'
                );*/
            
            // OUTPUT
            header('Content-type: application/json; charset=utf-8');

            echo json_encode($data);
        } else { // NO QUERY
            $this->denyAccess();
       }
        
        exit();//no profiling in JSON-answer
    }
    
    public function saveOpClasses()
    {
        header('Content-type: application/json; charset=utf-8');

        $data[] = $this->input->post('operator');
        $data[] = $this->input->post('classes');
        echo json_encode($data);
        
        exit();//no profiling in JSON-answer
        //@TODO 
    }
}
