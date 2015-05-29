<!DOCTYPE html>
<html>
    <head>
        <?php view('blocks/head', $__data) ?> 
    </head>
    
    <body>
        <?php view('blocks/header', $__data) ?> 
        
        <?php view($view, $__data) ?> 
        
        <?php view('blocks/footer') ?> 
    </body>
</html>
