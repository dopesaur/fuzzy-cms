<!DOCTYPE html>
<html>
    <head>
        <?php view('blocks/head', $__data) ?> 
    </head>
    
    <body>
        <article class="wrapper wrapper-login">
            <h1>Log in, admin</h1>
        
            <?php if ($error): ?>
            <p>
                <?php echo $error ?>
            </p>
            <?php endif; ?>
        
            <form action="<?php echo url('admin', 'login-post') ?>" 
                  class="form" method="post">
                <label>
                    Username: 
                    <input name="username" type="text"/>
                </label>
            
                <label>
                    Password: 
                    <input name="password" type="password"/>
                </label>
            
                <button type="submit">
                    Log in
                </button>
            </form>
        </article>
    </body>
</html>
