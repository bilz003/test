<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;


class MasterTable extends Table
{

    public function initialize(array $config)
    {

        $this->addBehavior('Timestamp');
        $this->addBehavior('Cake3Kendo.Kendo');

        $this->AccessLevel = [
            "index" => 1,
            "view" => 1,
            "add" => 1,
            "edit" => 1,
            "destroy" => 1,
            "export" => 1,
            "report" => 1,
            "download" => 1,
        ];


        $this->order = [$this->getAlias() . '.id' => 'desc'];
        $this->admin_order = ['id' => 'desc'];

        $this->kendoVars();
    }



    protected function kendoVars(){

        //show only this fields
        $this->kendoGridShow = [];
        $this->admin_kendoGridShow = [];

        $this->kendoGridDontShow = ['id', 'created', 'updated', 'modified', 'deleted'];
        $this->admin_kendoGridDontShow = ['id', 'created', 'updated', 'modified', 'deleted'];

        //hide these fields from grid
        $this->kendoGridHide = ['password'];
        $this->admin_kendoGridHide = ['password'];

        $this->passwordFields = ['password', 'confirm_password'];
        $this->admin_passwordFields = ['confirm_password'];


        //Underscore name of the associated model's className
        $this->kendoIgnoreAssoc = [];
        $this->admin_kendoIgnoreAssoc = [];


        //Underscore name of the associated model's className
        $this->kendoDontCreateDataSource = [];
        $this->admin_kendoDontCreateDataSource = [];


        /*Example
        $this->kendoCustomColumns = [
            [
                "values" => 'ds_issue_answers',
                "editor" => 'ed_issue_answers',
                'encoded' => false,
                'lockable' => false,
                'template' => "# if(typeof(issue_answersHABTMArray) == 'object') { # #= listAnswerOptions(issue_answersHABTMArray)# #}#",
                'field' => 'Issues_Answers',  //have this key always for Column ordering
                'title' => 'Answer Options',
            ]
        ];*/
        $this->kendoCustomColumns = [];
        $this->admin_kendoCustomColumns = [];

        $this->kendoCustomSchema = [];
        $this->admin_kendoCustomSchema = [];


        /*Example
        $this->kendoOverrideColumns = [
            'type' => [
                "values" => 'ds_question_types',
                "editor" => 'ed_question_types'
            ],
            'note' => [
                "editor" => 'ed_only_textarea'
            ]
        ];*/
        $this->kendoOverrideColumns = [];
        $this->admin_kendoOverrideColumns = [];


        /*Example
        $this->admin_kendoCommands = [
            ["name" => "edit"],
            ["name" => "destroy"],
            [
                "name" => "Detail",
                "imageClass" => "k-icon k-i-track-changes-enable",
                "click" => "but_detailsEdit"
            ]
        ];*/
        $this->kendoCommands = [
            ["name" => "edit"],
            ["name" => "destroy"]
        ];
        $this->admin_kendoCommands = [
            ["name" => "edit"],
            ["name" => "destroy"]
        ];


        $this->kendoNonEditable = ['id'];
        $this->admin_kendoNonEditable = ['id'];


        //Use this to make modification in the command column HEADER
        $this->kendoCommandColumn = [
            'firstCol' => false, //Whether the command column is the first or last column in the grid
            'title' => 'Details',
            //'width' => 600,
        ];
        $this->admin_kendoCommandColumn = $this->kendoCommandColumn;


        //FOR ORDERING OF KENDO COLUMNS IN THE GRID
        $this->kendoColumnOrder = [
            'id',
        ];
        $this->creditor_kendoColumnOrder= $this->kendoColumnOrder;


        //Change date time format
        $this->kendoDateFormat = 'dd/MM/yyyy';
        $this->admin_kendoDateFormat = $this->kendoDateFormat;

        $this->kendoDateTimeFormat = 'dd/MM/yyyy HH:mm:ss';
        $this->admin_kendoDateFormat = $this->kendoDateTimeFormat;
    }



    /*To get all the config values
     *
     * @created: 14 Mar, 2018
     * @params:
     * @Author: "Sandip Ghadge"
     * @return: Array
     */
    public function _getConfigValues($condition = [])
    {
        $this->Config = TableRegistry::get('Configs');
        $configs = $this->Config->find('list', [
            'keyField' => 'name',
            'valueField' => 'value',
            'conditions' => $condition
        ])->toArray();

        return $configs;
    }

}
