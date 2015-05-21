<h2>Posts</h2>

<p>
    <a href="/admin/posts-add/">
        Add a post
    </a>
</p>

<?php if ($posts): ?> 
<ul class="posts">
    <?php foreach ($posts as $post): ?> 
    <li>
        <h4>
            <a href="/admin/posts-edit/<?php echo $post['id'] ?>">
                <?php echo $post['title'] ?> 
            </a> -
            <a href="/admin/posts-remove/<?php echo $post['id'] ?>">
                remove post
            </a>
        </h4>
        
        <p class="date">
            <?php echo $post['date'] ?> 
        </p>
        
        <?php echo $post['content'] ?> 
    </li>
    <?php endforeach; ?> 
</ul>
<?php else: ?> 
    <p>
        No posts, create 
        <a href="/admin/posts-add/">one</a> instead.
    </p>
<?php endif; ?> 