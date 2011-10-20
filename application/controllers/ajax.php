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
    public function __construct() {
        parent::__construct();
        
        $this->output->enable_profiler(FALSE);
    }
    
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
    
    public function save_op_schools()
    {
        // Проверить наличие прав у пользователя на доступ к этому разделу
        if($this->allowAccessFor('admin'))
        {
            // Подгрузить модель
            $this->load->model('operator_model');
            // Получить user_id (оператора)
            $operator = $this->input->post('operator');
            // Получить schools[] (список школ)
            $schools = $this->input->post('schools');
            // Сохранить в модель список школ
            $result = $this->operator_model->save_operators_school_list($operator, $schools);
            
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($result);
            
            exit();
        }
    }
    /*
    public function get_timetable()
    {
        // Проверить наличие прав у пользователя на доступ к этому разделу
        if($this->allowAccessFor(array('admin', 'operator')))
        {
            
            // Подгрузить модель
            $this->load->model('timetables_model');
            // Получить class_id
            $class_id = $this->input->post('class');
            // Получить date (дату)
            $date = $this->input->post('date');
            // Сохранить в модель список школ
            $result = $this->timetables_model->get_subjects_by_class_and_date($class_id, $date);
            
            header('Content-type: application/json; charset=utf-8');
            echo json_encode($result);
            
            exit();
        }
    }
    */
}
