<h2>
    Posts
    
    <a class="small" href="/admin/posts-add/">
        Add a post
    </a>
</h2>

<?php if ($posts): ?> 
<ul class="posts">
    <?php foreach ($posts as $post): ?> 
    <li>
        <h3 class="post-title">
            <a href="/admin/posts-edit/<?php echo $post['id'] ?>">
                <?php echo $post['title'] ?> 
            </a>
            <a class="small" href="/admin/posts-remove/<?php echo $post['id'] ?>">
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
        <a href="/admin/posts-add/">one</a> instead.
    </p>
<?php endif; ?> 