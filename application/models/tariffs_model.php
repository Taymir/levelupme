<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tariffs_model
 *
 * @author U7
 */
class tariffs_model {
    private $tariffs = array(
        // СПИСОК ТАРИФНЫХ ПЛАНОВ
        0 => '0р, Без тарифа',
        1 => '100р, Оценки',
        2 => '150р, Оценки + инф-я',
        3 => '200р, Оценки + инф-я + аналитика',
        4 => '250р, Оценки + инф-я + граф.аналитика',
    );
    
    private $tariff_shortnames = array(
        // Короткие названия тарифов (Используется в массовой регистрации пользователей)
        0 => '0',
        1 => '100',
        2 => '150',
        3 => '200',
        4 => '250',
    );
    
    public function get_tariffs_for_widget()
    {
        $result = $this->get_tariffs_for_selector();
        
        // отсутствующий тариф не нужен, т.к. невозможно рассылать
        // сообщения незарегистрированным пользователям
        if(isset($result[0]))
            unset($result[0]);
        
        // Добавляем слово " и выше" к названию каждого тарифа
        foreach($result as $k=>$r)
            $result[$k] = $r . ' и выше';
        
        return $result;
    }
    
    public function get_tariffs_for_selector()
    {
        $result = $this->tariffs;
        
        return $result;
    }
    
    public /*OBSOLETE*/ function rule_send_email($tariff)
    {
        log_message('info', 'USING OBSOLETE METHOD rule_send_email');
        
        if($tariff >= 4)
            return true;
        return false;
    }
    
    public /*OBSOLETE*/ function rule_send_sms($tariff)
    {
        log_message('info', 'USING OBSOLETE METHOD rule_send_sms');
        
        if($tariff >= 1)
            return true;
        return false;
    }
    
    public function rule_send_grades_to_email($tariff)
    {
        // На данный момент не отсылаем данные об оценках на e-mail!
        //if($tariff >= 4)
        //    return true;
        return false;
    }
    
    public function rule_send_grades_to_sms($tariff)
    {
        if($tariff >= 1)
            return true;
        return false;
    }
    
    public function rule_send_msg_to_email($tariff)
    {
        if($tariff >= 4)
            return true;
        return false;
    }
    
    public function rule_send_msg_to_sms($tariff)
    {
        if($tariff >= 1)
            return true;
        return false;
    }
    
    public function is_valid_tariff_shortname($shortname)
    {
        return in_array($this->clean_tariff_shortname($shortname), $this->tariff_shortnames);
    }
    
    public function get_tariff_by_shortname($shortname)
    {
        return array_search($this->clean_tariff_shortname($shortname), $this->tariff_shortnames);
    }
    
    public function clean_tariff_shortname($shortname)
    {
        return str_replace(array('руб.', 'руб', 'р.', 'р'), '', $shortname); 
    }
}