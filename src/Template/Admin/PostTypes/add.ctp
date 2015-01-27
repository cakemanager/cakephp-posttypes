<?php
$fields = $postType['formFields'];
?>
<?= $this->Form->create($type); ?>
    <fieldset>
        <legend><?= __('Add ' . $postType['alias']) ?></legend>
<?php
foreach ($fields as $field => $options) {

    echo $this->Form->input($field, $options);
}
?>
    </fieldset>
        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>



<?php
//debug($postType);
//debug($type);
?>