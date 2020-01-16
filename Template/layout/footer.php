<?php if ($this->mantis->isMantisTask($task['id'])): ?>
<div>
    <a href="<?php echo $this->url->href('Mantis', 'show', array('plugin' => 'Mantis', 'task_id' => $task['id'], 'project_id' => $task['project_id'])); ?>" title="<?php echo t('Show Mantis status'); ?>" class="mantis-status-button"><i class="fa fa-fw fa-bug" aria-hidden="true"></i></a>
    <span class="mantis-status"></span>
</div>
<?php endif; ?>
