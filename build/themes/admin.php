<?php function theme_admin_auth (array $__data) {  extract($__data); ?><!DOCTYPE html>
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
<?php }  function theme_admin_index (array $__data) {  extract($__data); ?><h1>Howdy, admin!</h1>

<p>This is an admin panel.</p><?php }  function theme_admin_layout (array $__data) {  extract($__data); ?><!DOCTYPE html>
<html>
    <head>
        <?php view('blocks/head', $__data) ?> 
    </head>
    
    <body>
        <?php view('blocks/header', $__data) ?> 
        
        <article class="wrapper" id="wrapper">
            <?php view($view, $__data) ?> 
        </article>
    </body>
</html>
<?php }  function theme_admin_blocks_head (array $__data) {  extract($__data); ?><meta charset="UTF-8"/>
<title>
    <?php echo $title ?> - Default site
</title>
<link href="<?php echo url('assets/css/styles.css') ?>" 
      rel="stylesheet" 
      type="text/css"/>
<?php }  function theme_admin_blocks_header (array $__data) {  extract($__data); ?><header class="wrapper" id="header">
    <h1>
        <a href="<?php echo url('admin') ?>">
            Admin theme
        </a>
    </h1>
    
    <p>
        <a href="<?php echo url() ?>">Back to site</a> &mdash; 
        <a href="<?php echo url('admin', 'posts-view') ?>">Admin posts</a> &mdash;
        <a href="<?php echo url('admin', 'logout') ?>">Log out</a>
    </p>
</header>
<?php }  function theme_admin_posts_modify (array $__data) {  extract($__data); ?><h1><?php echo ucfirst($action) ?> a post</h1>

<form class="form" method="post">
    <?php 
        foreach ($form as $field => $data): 
            $type = array_get($data, 'type');
            $value = array_get($data, 'value', '');
    ?> 
    <label>
        <?php echo ucfirst($field) ?> 
        
        <?php if ($type === 'input'): ?> 
        <input name="<?php echo $field ?>" 
               type="text" 
               value="<?php echo $value ?>"/>
        <?php elseif ($type === 'text'): ?> 
        <textarea name="<?php echo $field ?>"><?php echo $value ?></textarea>
        <?php endif; ?> 
    </label>
    <?php endforeach; ?> 
    
    <div class="group">
        <button class="right" type="submit">
            Submit
        </button>
    </div>
</form><?php }  function theme_admin_posts_view (array $__data) {  extract($__data); ?><h2>
    Posts
    
    <a class="small" href="<?php echo url('admin', 'posts-add') ?>">
        Add a post
    </a>
</h2>

<?php if ($posts): ?> 
<ul class="posts">
    <?php foreach ($posts as $post): ?> 
    <li>
        <h3 class="post-title">
            <a href="<?php echo url('admin', 'posts-edit', $post['id']) ?>">
                <?php echo $post['title'] ?> 
            </a>
            <a class="small" href="<?php echo url('admin', 'posts-remove', $post['id']) ?>">
                remove post
            </a>
        </h3>
        
        <p class="date">
            <?php echo $post['date'] ?> 
        </p>
        
        <?php echo markdown($post['content']) ?> 
    </li>
    <?php endforeach; ?> 
</ul>
<?php else: ?> 
    <p>
        No posts, create 
        <a href="<?php echo url('admin', 'posts-add') ?>">
            one
        </a> instead.
    </p>
<?php endif; ?> <?php } ?>