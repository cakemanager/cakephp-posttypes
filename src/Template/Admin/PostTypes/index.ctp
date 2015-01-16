<?php

use Cake\Utility\Hash;

$fields = $postType['tableFields'];

?>

<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <?= $this->Menu->menu('main') ?>
    </ul>
</div>
<div class="actions index large-10 medium-9 columns" style="border-left: 0px">

    <h3><?= $postType['alias'] ?></h3>

    <?= ((!Hash::get($postType, 'actions.add')) ? '' : $this->Html->link('New ' . $postType['type'], ['action' => 'add', 'type' => lcfirst($postType['name'])])) ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>

                <?php foreach ($fields as $name => $options) : ?>
                    <?php if (!$options['hide']) : ?>
                        <th><?= $this->Paginator->sort($name) ?></th>
                    <?php endif; ?>
                <?php endforeach; ?>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($types as $type): ?>
                <tr>
                    <?php foreach ($fields as $name => $options) : ?>
                        <?php if (!$options['hide']) : ?>
                            <td>
                                <?=
                                (($options['get'] ? Hash::get($type->toArray(), $options['get']) : $type->$name));
                                ?>
                            </td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <td class="actions">
                        <?= ((!Hash::get($postType, 'actions.view')) ? '' : $this->Html->link(__('View'), ['action' => 'view', 'type' => lcfirst($postType['name']), $type->id])) ?>
                        <?= ((!Hash::get($postType, 'actions.edit')) ? '' : $this->Html->link(__('Edit'), ['action' => 'edit', 'type' => lcfirst($postType['name']), $type->id])) ?>
                        <?= ((!Hash::get($postType, 'actions.delete')) ? '' : $this->Form->postLink(__('Delete'), ['action' => 'delete', 'type' => lcfirst($postType['name']), $type->id], ['confirm' => __('Are you sure you want to delete # {0}?', $type->id)])) ?>
                    </td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->prev('< ' . __('previous')); ?>
            <?= $this->Paginator->numbers(); ?>
            <?= $this->Paginator->next(__('next') . ' >'); ?>
        </ul>
        <p><?= $this->Paginator->counter(); ?></p>
    </div>
</div>

<?php
//debug($types);
?>
