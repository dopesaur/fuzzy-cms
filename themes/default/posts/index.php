<h1>All posts</h1>

<p>Hello, there!</p>

<ul>
<?php foreach ($posts as $post): ?> 
    <li>
        <h2>
            <a href="/post/view/<?php echo $post['id'] ?>">
                <?php echo $post['title'] ?> 
            </a>
        </h2>
        
        <?php echo $post['content'] ?>
    </li>
<?php endforeach; ?>
</ul>