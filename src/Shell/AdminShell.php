<?php

namespace App\Shell;

use Cake\Console\Shell;

class AdminShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Admins');
    }


    public function main()
    {
        $this->out('To Create Admin, Use command "bin/cake admin add"');
    }


    public function add()
    {
        $username = $this->in('Enter Username');
        $password = $this->in('Enter Password ');

        if (!$username || !$password)
            return $this->abort('Enter a valid username and password');

        $admin = $this->Admins->newEntity([], ['validate' => false]);
        $admin->username = $username;
        $admin->password = $password;
        $admin->active = 'Y';

        if ($this->Admins->save($admin)) {
            $this->out('<info>Admin Created successfully</info>');
        } else {
            $this->err('<error>Sorry could not create Admin, try again. </error> ');
        }
    }
}