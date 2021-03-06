<?php
use Cake\Utility\Inflector;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php
        if ($this->fetch('Title'))
            echo $this->fetch('Title') . ' - REPLACE THIS';
        else
            echo Inflector::singularize(Inflector::humanize(Inflector::underscore($this->request->controller))) . ' ' . Inflector::humanize($this->request->action) . ' - REPLACE THIS';
        ?>
    </title>
    <?= $this->Html->meta('favicon.ico', '/img/header.png', ['type' => 'icon']); ?>


    <?= $this->Html->css('bootstrap.min') ?>
    <?= $this->Html->css('kendo/kendo.common.min') ?> <!--Kendo widgets for data visualization. -->
    <?= $this->Html->css('kendo/kendo.blueopal.min') ?> <!--Theme to use-->
    <?= $this->Html->css('kendo/kendo.blueopal.mobile.min') ?>
    <?= $this->Html->css('../toastr/build/toastr'); ?>
    <?= $this->Html->css('common-style') ?>

    <?= $this->Html->script('kendo/jquery.min') ?> <!--jQuery Library-->
    <?= $this->Html->script('kendo/kendo.all.min') ?> <!--Kendo all widgets-->
    <?= $this->Html->script('kendo/cultures/kendo.culture.en-AU.min'); ?>
    <?= $this->Html->script('bootstrap.min') ?>
    <?= $this->Html->script('../toastr/toastr'); ?>
    <?= $this->Html->script('tinymce/tinymce.min') ?>
    <?= $this->Html->script('tinymce/jquery.tinymce.min') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('scriptTop') ?>
    <script type="text/javascript">
        kendo.culture("en-AU");
    </script>

    <style>
        body {
            color: rgb(0, 0, 0) !important;
            font-size: 14px;
            line-height: 1.42857;
            padding-top: 1%;
        }

        .panel-login {
            border-color: #ccc;
            -webkit-box-shadow: 0px 2px 3px 0px rgba(0, 0, 0, 0.2);
            -moz-box-shadow: 0px 2px 3px 0px rgba(0, 0, 0, 0.2);
            box-shadow: 0px 2px 3px 0px rgba(0, 0, 0, 0.2);
        }

        .panel-login > .panel-heading {
            color: #00415d;
            background-color: #fff;
            border-color: #fff;
            text-align: center;
        }

        .panel-login > .panel-heading a {
            text-decoration: none;
            color: #666;
            font-weight: bold;
            font-size: 15px;
            -webkit-transition: all 0.1s linear;
            -moz-transition: all 0.1s linear;
            transition: all 0.1s linear;
        }

        .panel-login > .panel-heading a.active {
            color: #30b8c6;
            font-size: 18px;
        }

        .panel-login > .panel-heading hr {
            margin-top: 10px;
            margin-bottom: 0px;
            clear: both;
            border: 0;
            height: 1px;
            background-image: -webkit-linear-gradient(left, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0));
            background-image: -moz-linear-gradient(left, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0));
            background-image: -ms-linear-gradient(left, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0));
            background-image: -o-linear-gradient(left, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0));
        }

        .panel-login input[type="text"], .panel-login input[type="email"], .panel-login input[type="password"] {
            height: 45px;
            border: 1px solid #ddd;
            font-size: 16px;
            -webkit-transition: all 0.1s linear;
            -moz-transition: all 0.1s linear;
            transition: all 0.1s linear;
        }

        .panel-login input:hover,
        .panel-login input:focus {
            outline: none;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
            border-color: #ccc;
        }

        .btn-login:hover,
        .btn-login:focus {
            color: #fff;
            background-color: #53A3CD;
            border-color: #53A3CD;
        }

        .forgot-password {
            text-decoration: underline;
            color: #888;
        }

        .forgot-password:hover,
        .forgot-password:focus {
            text-decoration: underline;
            color: #666;
        }

        .btn-login {
            background-color: #59B2E0;
            outline: none;
            color: #fff;
            font-size: 14px;
            height: auto;
            font-weight: normal;
            padding: 14px 0;
            text-transform: uppercase;
            border-color: #59B2E6;
        }

        .btn-register {
            background-color: #1CB94E;
            outline: none;
            color: #fff;
            font-size: 14px;
            height: auto;
            font-weight: normal;
            padding: 14px 0;
            text-transform: uppercase;
            border-color: #1CB94A;
        }

        .btn-register:hover,
        .btn-register:focus {
            color: #fff;
            background-color: #1CA347;
            border-color: #1CA347;
        }
    </style>

    <?= $this->fetch('css') ?>
</head>
<body>
<div class="container-fluid">
    <div style="margin:0px auto; width:100%; text-align: center;">
        <?= $this->Html->image('header_logo.png', ['alt' => 'GPDC logo', 'style' => ['height: 140px;']]); ?>
    </div>
    <?php
    echo $this->Flash->render();

    /*if(isset($pageNote))
         echo $pageNote['top_page_note'];

    echo $this->fetch('content');

    if(isset($pageNote)){
        echo "</br>";
        echo $pageNote['bottom_page_note'];
    }*/
    ?>
</div>

<?= $this->fetch('scriptBottom') ?>

</body>
</html>