<?php

namespace Tigreboite\FunkylabBundle\Generator;

class SortableFormater extends Formater
{
    protected $type = 'Sortable';

    public function getFields()
    {
        $fields = parent::getFields();

        $foundOrdre = false;
        foreach ($fields as $field) {
            if ($field['varname'] == 'ordre') {
                $foundOrdre = true;
            }
        }

        if (!$foundOrdre) {
            throw new \RuntimeException("Field `ordre' missing in your entity");
        }

        return $fields;
    }

    public function getViews()
    {
        $files = parent::getViews();
        $sName = array();
        $TDName = array();
        $TRName = array();

        foreach ($this->getFields() as $field) {
            if ($field['visible']) {
                $sName[] = '{ "sName": "'.$field['varname'].'" }';
                if ($field['type'] == 'datetime') {
                    $TDName[] = '<td>{{ entity.'.$field['varname']."|date('d/m/Y H:i:s') }}</td>";
                } else {
                    $TDName[] = '<td>{{ entity.'.$field['varname'].' }}</td>';
                }
                $TRName[] = '<th>'.$field['name'].'</th>';
            }
        }

        foreach ($files as  $k => $body) {
            $body = str_replace('%s_fields%', implode(',', $sName), $body);
            $body = str_replace('%sname_fields%', implode(',', $sName), $body);
            $body = str_replace('%sortable_entity_fields%', implode('', $TRName), $body);
            $body = str_replace('%sortable_entity_values%', implode('', $TDName), $body);
            $files[$k] = $body;
        }

        return $files;
    }
}
