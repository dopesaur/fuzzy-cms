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
 function theme_default_layout (array $__data) {  extract($__data); ?><!DOCTYPE html>
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
<?php }  function theme_default_blocks_head (array $__data) {  extract($__data); ?><meta charset="UTF-8"/>
<title>
    <?php echo $title ?> - Default site
</title>
<link href="/assets/css/styles.css" rel="stylesheet" type="text/css"/>
<?php }  function theme_default_blocks_header (array $__data) {  extract($__data); ?><header class="wrapper" id="header">
    <h1>
        <a href="<?php echo url() ?>">
            Default theme
        </a>
    </h1>
    
    <p>
        Subheader, your title goes here - 
        <a href="<?php echo url('admin') ?>">Admin</a>
    </p>
</header>
<?php }  function theme_default_blocks_pagination (array $__data) {  extract($__data); ?><p>
    Page 
    <b><?php echo $pagination['page'] ?></b>
    out of 
    <b><?php echo $pagination['pages'] ?></b>
</p>

<ul class="pagination">
<?php foreach ($pagination['items'] as $page): ?> 
    <li>
        <?php if ((int)$page !== (int)$pagination['page']): ?>
        <a href="<?php echo "$url/$page" ?>">
            <?php echo $page ?> 
        </a>
        <?php else: ?>
        <span>
            <?php echo $page ?> 
        </span>
        <?php endif; ?>
    </li>
<?php endforeach; ?> 
</ul><?php }  function theme_default_posts_index (array $__data) {  extract($__data);  if ($posts): ?> 
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
        
        <?php echo markdown($post['content']) ?> 
    </li>
<?php endforeach; ?> 
</ul>

<?php if ($pagination['pages'] > 1): ?>
    <?php
        view('blocks/pagination', array(
            'url'        => url('posts/view'),
            'pagination' => $pagination
        )) 
    ?> 
<?php endif; ?>  
<?php else: ?> 
<p>No posts, m8.</p>
<?php endif; ?> <?php }  function theme_default_posts_post (array $__data) {  extract($__data); ?><h1 class="post-title">
    <?php echo $post['title'] ?> 
</h1>

<p class="date">
    <?php echo format_date($post['date']) ?> 
</p>

<?php echo markdown($post['content']) ?> 
<?php } ?>