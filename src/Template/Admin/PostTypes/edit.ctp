<?php
//debug($postType);

$fields = $postType['formFields'];
?>

<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <?= $this->Menu->menu('main') ?>
    </ul>
</div>
<div class="bookmarks form large-10 medium-9 columns">
    <?= $this->Form->create($type); ?>
    <fieldset>
        <legend><?= __('Add '.$postType['alias']) ?></legend>
        <?php
        foreach ($fields as $field => $options) {

            echo $this->Form->input($field, $options);

        }
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>



<?php
debug($type);
?>