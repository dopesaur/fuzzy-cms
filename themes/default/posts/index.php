<?php if ($posts): ?> 
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
