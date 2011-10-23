<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of operator_journal
 *
 * @author U7
 */
class operator_journal extends MY_Controller {
    public function __construct()
    {
        parent::__construct(array('operator', 'admin'));
        $this->load->model('grades_model');
        $this->load->model('operator_model');
    }
    
    public function index()
    {
        return $this->grades();
    }
    
    public function grades()
    {
        $this->load->model('timetables_model');
        $this->load->model('classes_model');
        $this->load->model('user_profile_model');
        $this->load->model('grades_sessions_model');
        
        $time = time();
        if($this->input->post('updatedate'))
            $time = $this->strdate_2_timestamp($this->input->post('date'));
        elseif($this->input->get('date'))
            $time = $this->strdate_2_timestamp($this->input->get('date'));
        $human_date = date('d.m.Y', $time);
        $db_date = date('Y-m-d', $time);
        
        $class = $this->operator_class();
        $schools_classes = $this->classes_model->get_schools_and_classes($this->operator_model->get_operators_school_list());
        if(isset($class)) {
            $grades = $this->grades_sessions_model->load_session($this->user_profile_model->getId(), $class->id, $db_date);
            if($grades !== null)
            {
                $this->load->vars($grades);
            }
            else
            {
                $subjects = $this->timetables_model->get_subjects_by_class_and_date($class->id, $human_date);
                $this->load_var('subjects', $subjects);
            }
            $students = $this->user_profile_model->get_users_by_class($class->id);// Переопределяет список учеников, загруженный из сессии
            $student_ids = $this->extract_ids_from_students($students);
            
            $this->load_var('students', $students);
            $this->load_var('date', $human_date);
        }
        if($this->grades_model->has_grades($db_date, $student_ids))
        {
            $this->load_var('error', 
            "Оценки на данную дату уже были отправлены. Чтобы просмотреть или изменить их, воспользуйтесь <a href=\"" . base_url() . "operator_journal/archive/?date=$human_date\">архивом</a>");
        }
        $this->load_var('schools_classes', $schools_classes);
        $this->load_style('datepicker_vista/datepicker_vista');
        $this->load_scripts('mootools-core', 'mootools-more', 'schoolClassWidget', 'datepicker/Locale.ru-RU.DatePicker',
        'datepicker/Picker', 'datepicker/Picker.Attach', 'datepicker/Picker.Date', 'showDialog', 'MUX.Dialog');
        $this->load_style('MUX.Dialog');
        
        return $this->load_view('operator_journal/grades_view', "Заполнение оценок");
    }
    
    public function archive()
    {
        $time = time();
        if($this->input->post('updatedate'))
            $time = $this->strdate_2_timestamp ($this->input->post('date'));
        elseif($this->input->get('date'))
            $time = $this->strdate_2_timestamp ($this->input->get('date'));
        $human_date = date('d.m.Y', $time);
        $db_date = date('Y-m-d', $time);
            
        $this->load->model('timetables_model');
        $this->load->model('classes_model');
        $this->load->model('user_profile_model');
        
        $class = $this->operator_class();
        $schools_classes = $this->classes_model->get_schools_and_classes($this->operator_model->get_operators_school_list());
        if(isset($class)) {
            $students = $this->user_profile_model->get_users_by_class($class->id);
            $grades = $this->grades_model->load_grades($db_date, $this->extract_ids_from_students($students));
            if($grades !== null)
            {
                $this->load_var('grades', $grades['grades']);
                $this->load_var('comments', $grades['comments']);
                $this->load_var('subjects', $grades['subjects']);
            }
            else
            {
                $subjects = $this->timetables_model->get_subjects_by_class_and_date($class->id, $human_date);
                $this->load_var('subjects', $subjects);
            }

            $this->load_var('students', $students);
            $this->load_var('date', $human_date);
        }
        $this->load_var('schools_classes', $schools_classes);
        $this->load_style('datepicker_vista/datepicker_vista');
        $this->load_scripts('mootools-core', 'mootools-more', 'schoolClassWidget', 'datepicker/Locale.ru-RU.DatePicker',
        'datepicker/Picker', 'datepicker/Picker.Attach', 'datepicker/Picker.Date', 'showDialog', 'MUX.Dialog');
        $this->load_style('MUX.Dialog');
        
        return $this->load_view('operator_journal/archive_view', "Журнал"); 
    }
    
    private function extract_ids_from_students($students)
    {
        $ids = null;
        foreach($students as $student)
            $ids[] = $student->profile_id;
        
        return $ids;
    }
    
    public function save_and_send()
    {
        $this->load->model('grades_sessions_model');
        $user_id = $this->user_profile_model->getId();
        $class_id = $this->input->post('class_id');
        $time = $this->strdate_2_timestamp($this->input->post('date'));
        $db_date = date('Y-m-d', $time);
        $human_date = date('d.m.Y', $time);
            
        if($this->input->post('tmp_save'))
        {    
            $data = $_POST;
            
            $this->grades_sessions_model->store_session($user_id, $class_id, $db_date, $data);
            
            $cur_date = date('d.m.Y');
            $sav_date = date('d.m.Y', $time);
            
            if($cur_date != $sav_date)
                $this->redirect_message('/operator_journal/grades/?date=' . $sav_date, 'saved');
            else
                $this->redirect_message('/operator_journal/grades', 'saved');
        } else {
            $data = array();
            $data['students'] = $this->input->post('students');
            $data['subjects'] = $this->input->post('subjects');
            $data['g'] = $this->input->post('g');
            $data['g_type'] = $this->input->post('g_type');
            $data['comments'] = $this->input->post('comments');
            $data = $this->filter_grades($data);

            $this->grades_sessions_model->clear_session($user_id, $class_id, $db_date);
            
            if($this->grades_model->has_grades($db_date, array_keys($data['students'])))
                return $this->show_message ("Расписание на эту дату уже было заполнено. Вы не можете повторно рассылать оценки на одну дату");
            
            $this->grades_model->save_grades($db_date, $data);
            $mailed = $this->send_grades($data, $human_date);

            if($mailed)
                return $this->show_message("Оценки сохранены и отправлены. $mailed получателей.");
            else
                return $this->show_message ("Оценки сохранены, но не отправлены, т.к. отсутствуют ученики с подходящими тарифами (или не заполнены их контактные данные).");
        }
    }
    
    public function save()
    {
        if($this->input->post('submit'))
        {
            $date = $this->strdate_2_timestamp($this->input->post('date'));
            $db_date = date('Y-m-d', $date);
            $human_date = date('d.m.Y', $date);
            
            $data = array();
            $data['students'] = $this->input->post('students');
            $data['subjects'] = $this->input->post('subjects');
            $data['grades'] = $this->input->post('grades');
            $data['comments'] = $this->input->post('comments');
            $data = $this->filter_grades($data);
            
            if($this->grades_model->has_grades($db_date, array_keys($data['students'])))
                return $this->show_message ("Расписание на эту дату уже было заполнено. Вы не можете повторно рассылать оценки на одну дату");
            
            $this->grades_model->save_grades($db_date, $data);
            $mailed = $this->send_grades($data, $human_date);

            if($mailed)
                return $this->show_message("Оценки сохранены и отправлены. $mailed получателей.");
            else
                return $this->show_message ("Оценки сохранены, но не отправлены, т.к. отсутствуют ученики с подходящими тарифами (или не заполнены их контактные данные).");
        }
    }
    
    private function filter_grades($data)
    {
        //@TODO: Вообще-то сюда можно было переместить всю очистку от ненужной шелухи (от пустых значений)
        if(!isset($data['grades']))
        {
            foreach($data['g'] as $user_profile_id => $subj_array)
            {
                foreach($subj_array as $num => $grades_array)
                {
                    // Добавляем заранее фиксированные типы оценок
                    $data['g_type'][$num][0] = '';
                    $data['g_type'][$num][1] = '';
                    
                    $grade_txt = '';
                    foreach($grades_array as $g_type_key => $grade)
                    {
                        if(isset($data['g_type'][$num][$g_type_key]) && trim($grade) != '')
                        {
                            if($grade_txt != '')
                               $grade_txt .=  ' ';
                            $grade_txt .= $grade . $data['g_type'][$num][$g_type_key];
                        }
                    }
                    $data['grades'][$user_profile_id][$num] = $grade_txt;
                }
            }
            unset($data['g_type']);
            unset($data['g']);
        }
        
        foreach($data['grades'] as $user_profile_id => $student_value)
        {
            if(!isset($data['students'][$user_profile_id]) || trim($data['students'][$user_profile_id]) == '')
            {
                unset($data['grades'][$user_profile_id]);
                unset($data['comments'][$user_profile_id]);
            }
            else {
                foreach($data['grades'][$user_profile_id] as $num => $grade)
                {
                    if(!isset($data['subjects'][$num]) || trim($data['subjects'][$num]) == '')
                    {
                        unset($data['grades'][$user_profile_id][$num]);
                        unset($data['comments'][$user_profile_id][$num]);
                    }
                }
            }
        }
        return $data;
    }
    
    private function strdate_2_timestamp($strdate)
    {
        $strdate = str_replace(array(',-/'), '.', $strdate);
        return strtotime($strdate);
    }
    
    private function send_grades($data, $date)
    {
        $this->load->model('tariffs_model');
        $this->load->model('mailings_model');
        $this->load->model('user_profile_model');
        
        $ci = & get_instance();
        $max_lessons = $ci->config->item('max_lessons');
        
        $students = $data['students'];
        $subjects = $data['subjects'];
        $grades   = $data['grades'];
        $comments = $data['comments'];
        
        $mailed = 0;
        
        $studentsData = $this->user_profile_model->get_userlist_by_profile_ids(array_keys($students));
        $mailing_pack = null;
        foreach($students as $user_profile_id => $student)
        {
            $pre_data = array();
            
            for($num = 1; ($num - 1) < $max_lessons; ++$num)
            {
                $subject = $subjects[$num];

                if(isset($grades[$user_profile_id][$num]) && trim($grades[$user_profile_id][$num]) != '')//@TODO: можно очищать в filter_grades (правда, надо будет тут поменять for на foreach)
                {
                    $pre_data[$num]['grades'] = $grades[$user_profile_id][$num];
                    $pre_data[$num]['subject'] = $subject;
                }
                if(isset($comments[$user_profile_id][$num]) && trim($comments[$user_profile_id][$num]) != '')//@TODO: можно очищать в filter_grades (правда, надо будет тут поменять for на foreach)
                {
                    $pre_data[$num]['comment'] = $comments[$user_profile_id][$num];
                    $pre_data[$num]['subject'] = $subject;
                }
            }
            
            if(sizeof($pre_data) > 0)
            {
                if(!$studentsData[$user_profile_id]->banned)
                {
                    if($mailed == 0)
                    {
                        //@HACK: создаем mailing_pack только когда готово первое письмо
                        $mailing_pack = $this->mailings_model->create_mailing_pack('grades');
                    }

                    $tariff = $studentsData[$user_profile_id]->tariff;

                    // Композиция письма
                    $email_title = "$student. Оценки $date";
                    $email_text = "<h2>$student<br><b>$date</b></h2><br>";//@TMP
                    $sms_text = "$date $student\n";

                    $email_text .= "<ul>";
                    foreach($pre_data as $num => $arr)
                    {
                        //@REFACTOR: это все должно быть во VIEW!!!!!!!
                        $subject = $pre_data[$num]['subject'];
                        $grade = isset($arr['grades']) ? $arr['grades'] : '';

                        $email_text .= "<li><b>$subject</b>: $grade";
                        $sms_text   .= "$subject: $grade";

                        if(isset($arr['comment']))
                        {
                            $comment = $arr['comment'];

                            $email_text .= "<br><i>$comment</i>";
                            $sms_text   .= "($comment)";
                        }
                        $email_text .= "</li>";
                        $sms_text   .= "\n";
                    }

                    if(!$this->tariffs_model->rule_send_grades_to_email($tariff) || empty($studentsData[$user_profile_id]->email))
                    {
                        $email_title = ''; $email_text = '';
                    }
                    if(!$this->tariffs_model->rule_send_grades_to_sms($tariff) || empty($studentsData[$user_profile_id]->phone))
                    {
                        $sms_text = '';
                    }

                    $data = array(
                        'email_title' => $email_title,
                        'email_text'  => $email_text,
                        'sms_text'    => $sms_text,
                        'user_profile_id' => $user_profile_id,
                        'pack_id'     => $mailing_pack
                    );

                    if(! (empty($email_text) && empty($sms_text)) )
                    {
                        $mailed += $this->mailings_model->add_single_mailing($data);
                    }
                }
            }
        }
        
        return $mailed;
    }
}