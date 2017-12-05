<h3><i class="fa fa-bug fa-fw"></i><?= t('Mantis Plugin') ?></h3>
<div class="panel">
    <?= $this->form->label(t('Mantis URL'), 'mantis_url') ?>
    <?= $this->form->text('mantis_url', $values) ?>

    <?= $this->form->label(t('Mantis Username'), 'mantis_username') ?>
    <?= $this->form->password('mantis_username', $values) ?>

    <?= $this->form->label(t('Mantis password'), 'mantis_password') ?>
    <?= $this->form->password('mantis_password', $values) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-blue"><?= t('Save') ?></button>
    </div>
</div>
