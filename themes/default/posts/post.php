<div class="cool-wrapper">
    <section class="wrapper">
        <h1 class="post-title">
            <?php echo $post['title'] ?> 
            
            <span class="date">
                <?php echo format_date($post['date']) ?> 
            </span>
        </h1>
                
        <p>
            <?php echo $post['description'] ?>
        </p>
    </div>
</div>

<article class="wrapper" id="wrapper">
    <?php echo markdown($post['content']) ?> 
</article>
