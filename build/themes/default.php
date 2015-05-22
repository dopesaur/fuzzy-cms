<?php

/**
 * Formats input date to 'dd.mm.yyyy' format
 * 
 * @param string $date
 * @return string
 */
function format_date ($date) {
    return date('d.m.Y', strtotime($date));
}
 function theme_layout (array $__data) { extract($__data); ?><!DOCTYPE html>
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
<?php }  function theme_blocks_head (array $__data) { extract($__data); ?><meta charset="UTF-8"/>
<title>
    <?php echo $title ?> - Default site
</title>
<link href="/assets/css/styles.css" rel="stylesheet" type="text/css"/>
<?php }  function theme_blocks_header (array $__data) { extract($__data); ?><header class="wrapper" id="header">
    <h1>
        <a href="/">
            Default theme
        </a>
    </h1>
    
    <p>
        Subheader, your title goes here - <a href="/admin/">Admin</a>
    </p>
</header>
<?php }  function theme_posts_index (array $__data) { extract($__data);  if ($posts): ?> 
<ul class="posts">
<?php foreach ($posts as $post): ?> 
    <li>
        <h1 class="post-title">
            <a href="/post/view/<?php echo $post['id'] ?>">
                <?php echo $post['title'] ?> 
            </a>
        </h1>
        
        <p class="date">
            <?php echo format_date($post['date']) ?> 
        </p>
        
        <?php echo $post['content'] ?> 
    </li>
<?php endforeach; ?> 
</ul>
<?php else: ?> 
<p>No posts, m8.</p>
<?php endif; ?> 
<?php }  function theme_posts_post (array $__data) { extract($__data); ?><h1 class="post-title">
    <?php echo $post['title'] ?> 
</h1>

<p class="date">
    <?php echo format_date($post['date']) ?> 
</p>

<?php echo $post['content'] ?> 
<?php } ?>