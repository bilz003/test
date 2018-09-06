<?php
namespace App\Controller\Api\Admin;

use App\Controller\Api\ApiController;
use Cake\Event\Event;


class MasterController extends ApiController
{

    public $paginate = [
        'limit' => 1
    ];

    public function initialize()
    {
        parent::initialize();
        if (empty($this->request->getSession()->read('Auth.admin.id'))) {
            http_response_code(404);
            echo "<div style='text-align: center; margin-top: 20%;'><h1>Error 404, Page not found</h1></div>";
            die;
        }
    }
}
