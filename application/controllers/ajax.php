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
    public function livesearch()
    {

        $query = $this->input->get('q');
        if($query)
        {
            //$this->load->model('profiles_model');
            // LOAD DATA
            //$data = array_values($this->profiles_model->list_friendnames_of($this->user_model->getId(), $this->user_model->getLevel(), $query));
            $data = array(
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
                );
            
            // OUTPUT
            header('Content-type: application/json');

            echo json_encode($data);
        } else { // NO QUERY
            $this->denyAccess();
       }
        
        exit();//no profiling in JSON-answer
    }
}
