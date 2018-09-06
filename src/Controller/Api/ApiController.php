<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Network\Exception;
use Cake\Event\Event;
use Cake\Utility\Inflector;
use Cake\Cache\Cache;

class ApiController extends AppController
{

    public $tableName;
    public $responseObjName;
    public $responseData;
    public $validationErrors = false;
    public $message;
    public $error;
    public $success = true;
    public $paginate = [
        'limit' => 1,
    ];

    public function initialize()
    {
        parent::initialize();
    }

    public function beforeFilter(Event $event)
    {
        $this->tableName = $this->request->getParam('controller');
        $this->responseObjName = Inflector::underscore($this->request->getParam('controller'));
    }

    public function index()
    {
        $modelName = $this->tableName;
        if ($this->request->getQuery('type') && $this->request->getQuery('type') == "dropdownList") {
            if (!empty($this->{$this->tableName}->getSchema()->getColumn('active')))
                if (!isset($this->paginate['conditions']['active']))
                    $this->paginate['conditions']['active'] = 'Y';


            if (!empty($this->{$this->tableName}->getSchema()->getColumn('status_id')))
                if (!isset($this->paginate['conditions']['status_id']))
                    $this->paginate['conditions']['status_id'] = 1;


            if (isset($this->paginate['conditions']) && !empty($this->paginate['conditions']))
                $conditions = $this->paginate['conditions'];
            else
                $conditions = false;


            if ($searchField = $this->request->getQuery('cascadeLookup'))
                if (isset($this->request->getQuery()['filter']['filters'])) {
                    $searchValue = $this->request->getQuery()['filter']['filters'][0]['value'];
                    $conditions = [$searchField => $searchValue];
                }

            $list = $this->{$this->tableName}->find('list', [
                'conditions' => $conditions,
                'order' => [$this->$modelName->order]
            ]);

            $items = [];
            foreach ($list as $key => $val) {
                $items[] = [
                    'value' => $key,
                    'text' => $val
                ];
            }
            echo json_encode($items);
            exit;

        } else {
            if (isset($this->paginate['contain']) && !empty($this->paginate['contain']))
                $contain = $this->paginate['contain'];
            else
                $contain = false;


            if (isset($this->paginate['conditions']) && !empty($this->paginate['conditions']))
                $conditions = $this->paginate['conditions'];
            else
                $conditions = false;


            $_GET['limit'] = isset($_GET['limit']) ? $_GET['limit'] : false;
            $_GET['page'] = isset($_GET['page']) ? $_GET['page'] : 1;
            $this->paginate = [
                'contain' => $contain,
                'conditions' => $conditions,
                'order' => $this->$modelName->order,
            ];
            if ($_GET['limit']) {
                $this->paginate['limit'] = $_GET['limit'];
                $this->paginate['page'] = $_GET['page'];
            }


            if (isset($this->request->getQuery()['cFilter']['filters'])) {
                $this->paginate['conditions'] = $this->Kendo->_kendoConvertFilter($this->request->getQuery('cFilter'));
                $test = $this->Kendo->kendoDecode($this->paginate['conditions']);
                if (strstr(key($this->paginate['conditions']['AND']), '.')) {
                } else {
                    $this->paginate['conditions'] = $test[$this->tableName];
                }
                $_GET['limit'] = false;
                unset($this->paginate['limit']);
            }
            if (isset($this->request->getQuery()['cOrder'][0]['field'])) {
                //Underscores in the field name causing ordering to fail
                $fieldname = preg_split("/_/", $this->request->getQuery()['cOrder'][0]['field'], 2);
                if (isset($fieldname[1])) {
                    $this->paginate['order'] = array($fieldname[1] => $this->request->getQuery()['cOrder'][0]['dir']);
                }
            }


            $ORM = $this->$modelName->find('all', $this->paginate);
            $totalRecords = $ORM->count();
            $retrievedData = $ORM->enableHydration(false)->toArray();
            $retrievedData = $this->_changeRetrievedData($retrievedData);
            $records = count($retrievedData);


            $dataSimple = $this->Kendo->kendoEncode($$modelName);
            $this->responseData['children'] = $dataSimple;
            $this->responseData['paging'] = [
                $this->responseObjName => [
                    'page' => $_GET['page'],
                    'current' => 1,
                    'count' => $totalRecords,
                    'limit' => $records,
                ]
            ];
        }
    }

    protected function _changeRetrievedData($data)
    {
        return $data;
    }

    /**
     * @throws NotFoundException
     */
    public function add()
    {
        try {
            if ($this->request->getData('action') == "create" && $this->request->getData('models')) {
                $kendoData = json_decode($this->request->getData('models'), true);
            } else {
                throw new Exception\NotFoundException(__('Not a valid request'));
            }

            if ($this->request->is('post')) {
                $kendoData = $this->Kendo->kendoDecode($kendoData);
                $kendoData = $this->_changeDataToSave($kendoData);
                $tableEntity = $this->{$this->tableName}->newEntity($kendoData);
                if ($this->{$this->tableName}->save($tableEntity)) {
                    $this->message = Inflector::humanize(Inflector::singularize(Inflector::underscore(($this->tableName)))) . ' Record Created';
                } else {
                    $this->success = false;
                    $this->message = 'Error while creating the record';
                    $this->validationErrors = $tableEntity->errors();
                }
            }
        } catch (Exception $e) {

        }
    }

    protected function _changeDataToSave($data)
    {
        return $data;
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        try {
            $data = json_decode($this->request->getData()['models'], true);
            if ($this->request->is(['post', 'put'])) {
                $kendoData = $this->Kendo->kendoDecode($data);
                $kendoData = $this->_changeDataToSave($kendoData);
                $tableEntity = $this->{$this->tableName}->get($id);
                $tableEntity = $this->{$this->tableName}->patchEntity($tableEntity, $kendoData);
                if ($this->{$this->tableName}->save($tableEntity)) {
                    $this->message = Inflector::humanize(Inflector::singularize(Inflector::underscore(($this->tableName)))) . ' Details Updated';
                } else {
                    $this->success = false;
                    $this->message = 'Error while updating the record';
                    $this->validationErrors = $tableEntity->errors();
                }
            }
        } catch (Exception $e) {

        }
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $record = $this->{$this->tableName}->get($id);
        $this->message = 'Record Deleted';
        if (!$this->{$this->tableName}->delete($record)) {
            $this->success = false;
            $this->message = 'Error while deleting the record';
        }
    }


    /*To change data during api save
     *
     * @created: 09 Mar, 2018
     * @params: $data(array) => Data to save
     * @Author: "Sandip Ghadge"
     * @return: Array
     */

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {

        $recipe = $this->{$this->tableName}->get($id);
        $this->set([
            'data' => $recipe,
            '_serialize' => ['data']
        ]);
    }


    /*To change data after retrieve
     *
     * @created: 24 Aug, 2018
     * @params: $data(array) => Data to change
     * @Author: "Sandip Ghadge"
     * @return: Array
     */

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->set([
            'success' => $this->success,
            $this->responseObjName => $this->responseData,
            'validationErrors' => $this->validationErrors,
            'message' => $this->message,
            '_serialize' => [$this->responseObjName, 'message', 'success', 'validationErrors'],
        ]);
    }
}
