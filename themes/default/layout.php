<!DOCTYPE html>
<html>
    <head>
        <?php view('blocks/head', $__data) ?> 
    </head>
    
    <body>
        <?php view('blocks/header', $__data) ?> 
        
        <article class="wrapper" id="wrapper">
            <?php view($view, $__data) ?> 
        </article>
        
        <?php view('blocks/footer') ?> 
    </body>
</html>
