<header class="wrapper" id="header">
    <h1>
        <a href="<?php echo url() ?>">
            <?php echo config('general.title', 'Default') ?>
        </a>
    </h1>
    
    <p>
        <a href="<?php echo url('posts') ?>">Latest posts</a>
        <?php foreach (data('pages') as $url => $title): ?> 
            &ndash; 
            <a href="<?php echo url($url) ?>">
                <?php echo $title ?> 
            </a>
        <?php endforeach; ?> 
        
        <?php if (is_admin()): ?> 
        &ndash; <a href="<?php echo url('admin') ?>">Admin</a>
        <?php endif; ?> 
    </p>
</header>
