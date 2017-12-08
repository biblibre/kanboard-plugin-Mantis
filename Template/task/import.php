<div class="page-header">
    <h2><?= $this->text->e($project['name']) ?> &gt; <?= t('Mantis Issue') ?></h2>
</div>

<?= $this->form->label(t('Issue ID'), 'id') ?>
<?= $this->form->text('id', $values, array(), array('required')) ?>
