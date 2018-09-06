<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('bootstrap.min', ['fullBase' => true]) ?>
    <?= $this->Html->css('kendo/kendo.common.min', ['fullBase' => true]) ?>
    <!--Kendo widgets for data visualization. -->
    <?= $this->Html->css('kendo/kendo.blueopal.min', ['fullBase' => true]) ?> <!--Theme to use-->
    <?= $this->Html->css('kendo/kendo.blueopal.mobile.min', ['fullBase' => true]) ?>
    <?= $this->Html->css('common-style', ['fullBase' => true]) ?>

    <?= $this->Html->script('kendo/jquery.min') ?> <!--jQuery Library-->
    <?= $this->Html->script('kendo/kendo.all.min', ['fullBase' => true]) ?> <!--Kendo all widgets-->
    <?= $this->Html->script('bootstrap.min', ['fullBase' => true]) ?>
    <?= $this->Html->script('Cake3Kendo.kendohelper', ['fullBase' => true]) ?>
</head>
<body>

<?php
echo $this->fetch('content')
?>

<?php
echo $this->Html->script('common-script') . "\n";
echo $this->fetch('script') . "\n";
echo $this->fetch('scriptBottom') . "\n";
echo $this->element('dynamic_script');
?>
</body>
</html>
