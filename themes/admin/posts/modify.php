<h1><?php echo ucfirst($action) ?> a post</h1>

<form class="form" method="post">
    <?php 
        foreach ($form as $field => $data): 
            $type = array_get($data, 'type');
            $value = array_get($data, 'value', '');
    ?> 
    <label>
        <?php echo ucfirst($field) ?> 
        
        <?php if ($type === 'input'): ?> 
        <input name="<?php echo $field ?>" 
               type="text" 
               value="<?php echo $value ?>"/>
        <?php elseif ($type === 'text'): ?> 
        <textarea name="<?php echo $field ?>"><?php echo $value ?></textarea>
        <?php endif; ?> 
    </label>
    <?php endforeach; ?> 
    
    <div class="group">
        <button class="right" type="submit">
            Submit
        </button>
    </div>
</form>