<h2>Hello there!</h2>

<p>
    This is my blog. I post here posts. 
    Here, you can see my latest blog posts.
</p>

<?php if ($posts): ?> 
<ul class="posts">
<?php foreach ($posts as $post): ?> 
    <li>
        <h1 class="post-title">
            <a href="<?php echo url('post', 'view', $post['id']) ?>">
                <?php echo $post['title'] ?> 
            </a>
        </h1>
        
        <p class="date">
            <?php echo format_date($post['date']) ?> 
        </p>
        
        <p><?php echo $post['description'] ?></p>
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
<?php endif; ?> 
