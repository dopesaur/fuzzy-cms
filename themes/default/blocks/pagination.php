<p>
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
</ul>