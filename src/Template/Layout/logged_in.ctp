<?php
use Cake\Utility\Inflector;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <?= $this->Html->charset() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?php
        if ($this->fetch('Title'))
            echo $this->fetch('Title') . ' - REPLACE THIS';
        else {
            $action = Inflector::humanize($this->request->action);
            if ($this->request->action == 'index')
                $action = 'List';
            echo Inflector::singularize(Inflector::humanize(Inflector::underscore($this->request->controller))) . ' ' . $action . ' - REPLACE THIS';
        }
        ?>
    </title>
    <?= $this->Html->meta('favicon.ico', '/img/header_logo.png', ['type' => 'icon']); ?>

    <?= $this->Html->css('bootstrap.min') ?>
    <?= $this->Html->css('kendo/kendo.common.min') ?> <!--Kendo widgets for data visualization. -->
    <?= $this->Html->css('kendo/kendo.blueopal.min'); ?> <!--Theme to use-->
    <?= $this->Html->css('kendo/kendo.blueopal.mobile.min'); ?>
    <?= $this->Html->css('../toastr/build/toastr'); ?>
    <?= $this->Html->css('common-style'); ?>
    <?php
    if ($this->request->prefix == 'admin')
        echo $this->Html->css('admin-style');
    ?>

    <?= $this->Html->script('kendo/jquery.min') ?> <!--jQuery Library-->
    <?= $this->Html->script('kendo/kendo.all.min') ?> <!--Kendo all widgets-->
    <?= $this->Html->script('kendo/cultures/kendo.culture.en-AU.min'); ?>
    <?= $this->Html->script('bootstrap.min') ?>
    <?= $this->Html->script('../toastr/toastr'); ?>
    <?= $this->Html->script('tinymce/tinymce.min') ?>
    <?= $this->Html->script('tinymce/jquery.tinymce.min') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <script type="text/javascript">
        kendo.culture("en-AU");
    </script>
</head>
<body>

<header class="pageheader">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <?= $this->element('header', ['logo_url' => $logo_url]) ?>
            </div>
        </div>
    </div>
</header>

<section class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div id="sidebar" class="col-md-2 col-sm-3 sidebar">
                <?php echo $this->element('side-menu'); ?>
            </div>
            <div id="content" class="col-md-10 col-md-offset-2 col-sm-9 col-sm-offset-3 main">
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
            <div style="padding-top: 20px;" class="col-md-10 col-md-offset-2 col-sm-9 col-sm-offset-3 main"></div>
        </div>
    </div>
</section>
<footer class="pagefooter">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-2 col-sm-9 col-sm-offset-3">
                <br/>
                <br/>
                <?= $this->element('footer') ?>
            </div>
        </div>
    </div>
</footer>


<?php
echo $this->Html->script('common-script') . "\n";
echo $this->fetch('script') . "\n";
echo $this->fetch('scriptBottom') . "\n";
echo $this->element('dynamic_script');
?>
</body>
</html>
