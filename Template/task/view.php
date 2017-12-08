<h2><?= t('Mantis Issue') . ' ' . sprintf('<a href="%s">#%s</a>', $external_task->getUri(), $external_task->getIssueId()) ?></h2>

<ul class="mantis-tags">
<?php foreach ($external_task->getIssue()->tags as $tag): ?>
    <li class="mantis-tag"><?= $this->text->e($tag->name) ?></li>
<?php endforeach ?>
</ul>

<table>
    <tr>
        <th class="column-25"><?= t('Status') ?></th>
        <td class="column-25"><?= $external_task->getIssue()->status->name ?></td>

        <th class="column-25"><?= t('Creation Date') ?></th>
        <td class="column-25"><?= $this->dt->date($external_task->getIssue()->date_submitted) ?></td>
    </tr>
    <tr>
        <th><?= t('Reporter') ?></th>
        <td><?= $this->text->e($external_task->getIssue()->reporter->name) ?></td>

        <th><?= t('Modification Date') ?></th>
        <td><?= $this->dt->date($external_task->getIssue()->last_updated) ?></td>
    </tr>
    <tr>
        <th><?= t('Assignee') ?></th>
        <td>
            <?= $this->text->e($external_task->getIssue()->handler->name) ?>
        </td>

        <th><?= t('Project') ?></th>
        <td><?= $this->text->e($external_task->getIssue()->project->name) ?></td>
    </tr>
</table>
