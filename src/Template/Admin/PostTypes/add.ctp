<?php
$fields = $postType['formFields'];
?>

<h3><?= $postType['alias'] ?></h3>

<?= $this->Html->link('All ' . $postType['type'], ['action' => 'index', 'type' => lcfirst($postType['name'])]) ?>

<hr>

<?= $this->Form->create($type); ?>
<fieldset>
    <legend><?= __('Add ' . $postType['type']) ?></legend>
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