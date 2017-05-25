<?php

namespace Tigreboite\FunkylabBundle\Generator;

class DatagridFormater extends Formater
{
    protected $type = 'Datagrid';

    public function getViews()
    {
        $files = parent::getViews();
        $sName = array();
        $TRName = array();

        foreach ($this->getFields() as $field) {
            if ($field['visible']) {
                $TRName[] = '<th>'.$field['name'].'</th>';
                $sName[] = '{ "sName": "'.$field['varname'].'" }';
            }
        }

        foreach ($files as  $k => $body) {
            $body = str_replace('%sname_fields%', implode(',', $sName), $body);
            $body = str_replace('%datagrid_entity_fields%', implode('', $TRName), $body);
            $body = str_replace('%sname_fields%', implode(',', $sName), $body);
            $files[$k] = $body;
        }

        return $files;
    }
}
