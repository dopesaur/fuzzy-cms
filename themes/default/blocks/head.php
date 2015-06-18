<meta charset="UTF-8"/>
<title>
    <?php isset($title) and print($title) ?> - 
    <?php echo config('general.title', 'Default') ?>
</title>
<link href="<?php echo url('assets/css/styles.css') ?>" 
      rel="stylesheet" 
      type="text/css"/>
