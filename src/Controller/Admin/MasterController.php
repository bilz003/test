<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;

class MasterController extends AppController
{
    public function initialize()
    {

        parent::initialize();

        $this->loadComponent('Auth', [
            'loginAction' => ['controller' => 'Admins', 'action' => 'login'],
            'loginRedirect' => ['controller' => 'Admins', 'action' => 'index'],
            'logoutRedirect' => ['controller' => 'Admins', 'action' => 'login'],
            'authenticate' => [
                'Form' => [
                    'finder' => 'auth',
                    'userModel' => 'Admins',
                    'fields' => ['username' => 'username', 'password' => 'password']
                ]
            ]

        ]);
        $this->Auth->__set('sessionKey', 'Auth.admin');

        //To use specific folder for the different portal/user in WYSIWYG editor
        //$this->request->getSession()->write("RF.subfolder", 'admin/');
    }


    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        if ($this->Auth->user('id')) {
            $this->viewBuilder()->setLayout('logged_in');
        } else {
            $this->viewBuilder()->setLayout('login');
        }
    }
}