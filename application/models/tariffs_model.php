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
        0 => 'Без тарифа, 0 руб.',
        1 => 'Оценки, 100 руб.',
        2 => 'Оценки + инф-я, 150 руб.',
        3 => 'Оценки + инф-я + аналитика, 200 руб.',
        4 => 'Оценки + инф-я + граф.аналитика, 250 руб.',
    );
    
    public function get_tariffs_for_widget()
    {
        $result = $this->get_tariffs_for_selector();
        
        // Добавляем слово " и выше" к названию каждого тарифа
        foreach($result as $k=>$r)
            $result[$k] = $r . ' и выше';
        
        return $result;
    }
    
    public function get_tariffs_for_selector()
    {
        $result = $this->tariffs;
        
        // отсутствующий тариф не нужен, т.к. невозможно рассылать
        // сообщения незарегистрированным пользователям
        if(isset($result[0]))
            unset($result[0]);
        
        return $result;
    }
}

?>
