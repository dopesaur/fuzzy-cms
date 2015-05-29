<h2>
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
        
        <p>
            <?php echo $post['description'] ?> 
        </p>
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
<?php endif; ?> 