<section class="post">
    <h1 class="post-title">
        <?php echo $post['title'] ?> 
    </h1>

    <p class="date">
        <?php echo format_date($post['date']) ?> 
    </p>
                
    <p>
        <?php echo $post['description'] ?> 
    </p>

    <?php echo markdown($post['content']) ?> 
</section>
