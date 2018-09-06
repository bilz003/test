<?php
namespace App\View\Helper;

use Cake\View\Helper;
use Cake\Utility\Inflector;

class KendoHelper extends Helper{
    public function createGrid($name = null, $options = array(), $customArray = array()) {
        if(isset($options['gridDivID'])) {
            $gridDivID = $options['gridDivID'];
        } else {
            $gridDivID = "grid";
        }
        if (!isset($options['dataSource'])) {
            $options['dataSource'] = 'mainData';
        }
        if (!isset($options['filterable'])) {
            $options['filterable'] = "{extra:false}";
        }
        if (!isset($options['scrollable'])) {
            $options['scrollable'] = "true";
        }
        if (!isset($options['columnMenu'])) {
            $options['columnMenu'] = "true";
        }
        if (!isset($options['sortable'])) {
            $options['sortable'] = "true";
        }
        if (!isset($options['resizable'])) {
            $options['resizable'] = "true";
        }
        if (!isset($options['edit'])) {
            $options['edit'] = "function(e){
                showToolTip();
            }";
        }
        if (!isset($options['pageable'])) {
            $options['pageable'] = "{
                refresh: true,
                pageSizes: [5,10,20,50,100],
                buttonCount: 10
            }";
        }
        if (!isset($options['toolbar'])) {
            $options['toolbar'] = "[{
                        template: 
                        '<div>'+
                        '   <div style=\"float:left;\">'+
                        '       <h2>" . Inflector::humanize(Inflector::underscore($cName)) . "</h2>'+
                        '       </div><div style=\"float:right;margin: 2% 1% 1% 1%;\">'+
                        '       <a title=\"Add new\" class=\"k-button k-button-icontext k-grid-add\" href=\"\\\\#\">'+
                        '           <span class=\"k-icon k-i-plus-outline\"></span>'+
                        '           Add'+
                        '       </a>'+
                        '   </div>'+
                        '</div>'
                        }]";
        }
        if (!isset($options['editable'])) {
            $options['editable'] = "'popup'";
        }
        $customString = "";
        foreach($customArray as $key => $val) {
            $customString .= ", " . $key . ":" . $val;
        }

        $html = "$(\"#" . $gridDivID . "\").kendoGrid({
            dataSource: " . $options['dataSource'] . ",
            columns: " . $options['columns'] . ",
            filterable: " . $options['filterable'] . ",
            scrollable: " . $options['scrollable'] . ",
            columnMenu: " . $options['columnMenu'] . ",
            sortable: " . $options['sortable'] . ",
            resizable: " . $options['resizable'] . ",
            pageable: " . $options['pageable'] . ",
            toolbar: " . $options['toolbar'] . ",
            edit: " . $options['edit'] . ",
            editable: " . $options['editable'] . "
            " . $customString . "
        });";
        return $html;
    }



    public function gridFunctions($controller = null, $customArray = array()) {
        $html = "";
        foreach($customArray as $key => $val) {
            $html .= $val."\n";
        }
        $html .= "function custom_editRecord(e) {\n".
                "    e.preventDefault();\n".
                "    var dataItem = this.dataItem($(e.currentTarget).closest(\"tr\"));\n".
                "    var url = \"./" . $controller . "/edit/\" + dataItem." . $controller . "_" . $controller . "ID;\n".
                "    window.location.href = url;\n".
                "}\n";
        return $html;
    }


    /**
     * Returns a js function(s) for provided models that convert ds_XXXXXXX to object instead a function
     *
     * @param null $modelArray array of Model name to be used
     * @return string javascript Code to display the buttons
     */
    public function ds_and_ed($modelArray=null){

        $html = '';
        if(is_array($modelArray) && !empty($modelArray)){
            foreach($modelArray as $model){
                $html .= "var ds_$model = new kendo.data.DataSource({\n".
                         "    transport: {\n".
                         "        read: {\n".
                         "        url: '/db/api/admin/'.$model.'.json',\n".
                         "        type: 'get',\n".
                         "        dataType: 'json',\n".
                         "        async: false,\n".
                         "        data: {\n".
                         "            type: 'dropdownList'\n".
                         "            }\n".
                         "        }\n".
                         "    }\n".
                         "    });\n".
                         "ds_$model.read()\n";

                $html .= "function ed_$model(container, options) {\n".
                         "    $('<input name=\"' + options.field + '\"/>').appendTo(container).kendoDropDownList({\n".
                         "         autoBind: false,\n".
                         "         optionLabel: {\n".
                         "             text: 'Please choose',\n".
                         "             value: '0'\n".
                         "         },\n".
                         "         dataSource: ds_$model,\n".
                         "         dataTextField: 'text',\n".
                         "         dataValueField: 'value'\n".
                         "    });\n".
                         "}\n";
            }
        }
        return $html;
    }


}