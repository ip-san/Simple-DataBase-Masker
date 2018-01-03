<?php 
    if (!isset($type)) {
        $type = 'text';
    }

    $class = (string)'';
    $message = getErrorMsg($name);
    if (!empty($message)) 
    {
        $class = 'error';
    }
     
    $list=(string)'';
    $data_list_name = $name . '_data_list';
    
    if (isset($data_list) && is_array($data_list)) {
        $list = 'list="' . $data_list_name . '"';
    }
?>
<label for="<?=$name?>"><?=$text_name?></label>
<input type="<?=$type?>" id="<?=$name?>" class="<?=$class?>"
       name="<?=$name?>" value="<?=getSession($name)?>" <?=$list?>>
<?php if (isset($data_list) && is_array($data_list) 
        && (count($data_list) > 0)): ?>
    <datalist id="<?=$data_list_name?>">
        <?php foreach ($data_list as $value): ?>
            <option value="<?=$value?>">
        <?php endforeach; ?>
    </datalist>
<?php endif; ?>