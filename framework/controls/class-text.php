<?php

namespace syntaxthemes\restaurant;

/**
 * Description of synth-control-text
 *
 * @author Ryan Haworth
 */
class syn_text extends syn_control {

    /**
     * 
     * @param type $id
     * @param type $name
     * @param type $default_value
     * @param type $validation
     */
    public function __construct($id, $name, $default_value, $data, $validation) {

        parent::__construct($id, $name, $default_value, $data, $validation);
    }

    public function render() {

        $html = '<input type="text" id="' . $this->_id . '" name="' . $this->_name . '" value="' . ("" != $this->_value ? $this->_value : $this->_defaultValue) . '" ' . $this->_validation . $this->data_attrs() . ' />';

        return $html;
    }

    public function test_control() {

        $this->_id = 'syn_text_control';
        $this->_name = 'syn_text_title';

        echo $this->render();
    }

}
