<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Utility\Inflector;
use Cake\Mailer\Email;
use Cake\Utility\Xml;


class AppController extends Controller
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Cake3Kendo.Kendo');
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Cookie', ['expiry' => '1 day']);
    }


    public function index()
    {
        $modelName = $this->name;
        $prefix = Inflector::camelize($this->request['prefix']);
        $cName = Inflector::underscore($this->request['controller']);

        $kendoModelArray = json_encode($this->{$this->name}->makeKendoModel($this));

        $kendoAssociations = $this->$modelName->makeKendoAssociations($this);
        $kendoDsData = $kendoAssociations['kendoDsData'];
        $kendoEdData = $kendoAssociations['kendoEdData'];

        $kendoGridCols = $this->{$this->name}->makeKendoGridCols($this);
        $kendoGridCols = $this->Kendo->convertKendoGridCols($kendoGridCols);

        $this->set(compact('kendoModelArray', 'cName', 'kendoGridCols', 'kendoDsData', 'kendoEdData'));
        if (!file_exists(APP . 'Template' . DS . $prefix . DS . $this->request->getParam('controller') . DS . 'index.ctp')) {
            $this->render(DS . $prefix . DS . "/Scaffold/index");
        }
    }


    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);

        //Data from PageNote table to display in page top/bottom
        /*$this->loadModel('PageNotes');
        $request = $this->request;
        $slug = Inflector::underscore($request->getParam('controller')) . (($request->getParam('action') == 'index') ? '' : '/' . $request->getParam('action'));
        $PageNote = $this->PageNotes->find('all')
            ->where(['slug' => $slug, 'active' => 'Y'])
            ->first();
        if (!empty($PageNote)) {
            $this->set('pageNote', $PageNote->toArray());
        }*/

        //Data from toolTip table, to display tooltip for the fields
        /*$tableNames[] = inflector::underscore($this->request->controller);
        $this->loadModel('Tooltips');
        $tooltip=$this->Tooltips->find('all')
            ->select(['tooltip', 'field_name'])
            ->where(['table_name IN'=> $tableNames])
            ->hydrate(false)->toArray();
        $this->set('ToolTips', $tooltip);*/
    }


    /*To create a folder with permission
     *
     * @created: 15 Mar, 2018
     * @params1: $folderPath(string) => Path of the folder to create
     * @params2: $permission(number) => Permission to give to the folder
     * @params3: $recursive(bool) => Does the permission is apply to the sub folders
     * @Author: "Sandip Ghadge"
     * @return: bool
     */
    protected function _createFolder($folderPath, $permission = 0777, $recursive = true)
    {
        if($folderPath && !file_exists($folderPath)){
            $oldMask = umask(0);
            mkdir($folderPath, $permission, $recursive);
            umask($oldMask);
            return true;
        }
        return false;
    }


    /*To send email
     *
     * @created: 21 Mar, 2018
     * @params1: $toMail(string) => Recipient Email ID
     * @params2: $subject(string) => Subject of the Email
     * @params3: $body(string) => Body of the Email
     * @Author: "Sandip Ghadge"
     * @return: Array
     */
    protected function _send_email($from = NULL, $toMail = NULL, $subject = '', $body = '', $cc = '', $attachments = []){
        $this->loadModel('Configs');
        $sender_email = $from;
        $sender_name = 'ADMIN';
        $email = new Email('default');
        $email->setFrom([$sender_email => $sender_name])
            ->setEmailFormat('both')
            ->setTo($toMail)
            ->setSubject($subject);

        if($cc)
            $email->setCc($cc);
        if($attachments)
            $email->setAttachments($attachments);


        if($email->send($body))
            return true;
        else
            return false;
    }


    //Convert Array to XML
    protected function _getArrayFromXML($xmlString = [])
    {

        $xmlArray = Xml::toArray(Xml::build($xmlString));

        return $xmlArray;
    }


    //Convert XML to Array
    protected function _getXMLFromArray($xmlArray = [])
    {

        $xmlObject = Xml::fromArray($xmlArray);
        $xmlString = $xmlObject->asXML();

        return $xmlString;
    }


    /* Call to a URL using CURL, with specified parameters
     *
     * @created: 10 June, 2018
     * @params:
     * @Author: "Sandip Ghadge"
     * @return: Array
     */
    protected function _callURL($url, $headers, $type = 'GET', $params = NULL, $showResponseHeader = false)
    {
        $url = $url;
        $request = curl_init();

        if (isset($headers)) {
            curl_setopt($request, CURLOPT_HTTPHEADER, $headers);
        }

        if ($type == 'POST') {
            curl_setopt($request, CURLOPT_POST, true);
            curl_setopt($request, CURLOPT_POSTFIELDS, $params);
        } elseif ($type == 'PUT') {
            curl_setopt($request, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($request, CURLOPT_POSTFIELDS, $params);
        } else {
            if ($params)
                $url .= '?' . $params;
        }


        curl_setopt($request, CURLOPT_URL, $url);
        curl_setopt($request, CURLOPT_HEADER, $showResponseHeader);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_CRLF, false);
        $response = curl_exec($request);
        curl_close($request);

        return $response;
    }
}
