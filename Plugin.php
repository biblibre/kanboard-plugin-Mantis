<?php

namespace Kanboard\Plugin\Mantis;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Plugin\Mantis\ExternalTask\MantisExternalTaskProvider;
use Kanboard\Plugin\Mantis\Action\UpdateAction;

class Plugin extends Base
{
    public function initialize()
    {
        $this->hook->on('template:layout:css', array('template' => 'plugins/Mantis/notifs.css'));
        $this->template->hook->attach('template:config:integrations', 'Mantis:config/integration');
        $this->template->hook->attach('template:board:task:icons', 'Mantis:task/icons');

        $this->externalTaskManager->register(new MantisExternalTaskProvider($this->container));

        $this->actionManager->register(new UpdateAction($this->container));
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginName()
    {
        return 'Mantis';
    }

    public function getPluginDescription()
    {
        return t('Integration with Mantis BT');
    }

    public function getPluginAuthor()
    {
        return 'Julian Maurice';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/biblibre/kanboard-plugin-mantis';
    }
}

