<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 07.02.19
 * Time: 12:37
 */

class Html
{
    static public function echoInput($type, $params)
    {
        switch ($type) {
            case 'number':
                $self = [];
                $self['name'] = (isset($params['name']) && $params['name'] !== '') ? 'name="' . $params['name'] . '"' : '';
                $self['id'] = (isset($params['id']) && $params['id'] !== '') ? 'id="' . $params['id'] . '"' : '';
                $self['value'] = (isset($params['value']) && $params['value'] !== '') ? 'value="' . $params['value'] . '"' : '';
                $self['min'] = (isset($params['min']) && $params['min'] !== '') ? 'min="' . $params['min'] . '"' : '';
                $self['max'] = (isset($params['max']) && $params['max'] !== '') ? 'max="' . $params['max'] . '"' : '';
                $self['step'] = (isset($params['step']) && $params['step'] !== '') ? 'step="' . $params['step'] . '"' : '';
                $self['size'] = (isset($params['size']) && $params['size'] !== '') ? 'size="' . $params['size'] . '"' : '';
                $self['align'] = (isset($params['align']) && $params['align'] !== '') ? 'align="' . $params['align'] . '"' : '';
                $self['class'] = (isset($params['class']) && $params['class'] !== '') ? 'class="' . $params['class'] . '"' : '';
                $self['disabled'] = (isset($params['disabled'])) ? 'disabled' : '';
                $output = '<input type="' . $type . '" ' . implode(' ', $self) . '>';
                break;
            case 'text':
                $self = [];
                $self['name'] = (isset($params['name']) && $params['name'] !== '') ? 'name="' . $params['name'] . '"' : '';
                $self['id'] = (isset($params['id']) && $params['id'] !== '') ? 'id="' . $params['id'] . '"' : '';
                $self['value'] = (isset($params['value']) && $params['value'] !== '') ? 'value="' . $params['value'] . '"' : '';
                $self['size'] = (isset($params['size']) && $params['size'] !== '') ? 'size="' . $params['size'] . '"' : '';
                $self['align'] = (isset($params['align']) && $params['align'] !== '') ? 'align="' . $params['align'] . '"' : '';
                $self['class'] = (isset($params['class']) && $params['class'] !== '') ? 'class="' . $params['class'] . '"' : '';
                $self['disabled'] = (isset($params['disabled'])) ? 'disabled' : '';
                $output = '<input type="' . $type . '" ' . implode(' ', $self) . '>';
                break;
            default:
                $output = '';
                break;
        }
        return $output;
    }

    static public function shellTd($string){
        return '<td>' . $string . '</td>';
    }
    static public function shellTr($string){
        return '<tr>' . $string . '</tr>';
    }
    static public function shellTh($string){
        return '<th>' . $string . '</th>';
    }
}

// align='center'class='input_blue' disabled