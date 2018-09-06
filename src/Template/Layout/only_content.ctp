<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->Html->charset() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<?php
    echo $this->fetch('content')
?>

<?php
    echo $this->Html->script('common-script')."\n";
    echo $this->fetch('script')."\n";
    echo $this->fetch('scriptBottom')."\n";
    echo $this->element('dynamic_script');
?>
</body>
</html>