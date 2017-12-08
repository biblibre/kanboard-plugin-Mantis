<?php

namespace Kanboard\Plugin\Mantis;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Model\TaskModel;
use Kanboard\Plugin\Mantis\ExternalTask\MantisTaskProvider;
use Kanboard\Plugin\Mantis\Action\CheckAction;
use Kanboard\Plugin\Mantis\Subscriber\MantisSubscriber;

class Plugin extends Base
{
    public function initialize()
    {
        $this->template->hook->attach('template:config:integrations', 'Mantis:config/integration');
        $this->template->hook->attach('template:layout:css', 'plugins/Mantis/assets/css/Mantis.css');

        $provider = new MantisTaskProvider($this->container);
        $this->externalTaskManager->register($provider);

        $action = new CheckAction($this->container);
        $this->actionManager->register($action);

        $subscriber = new MantisSubscriber($this->container);
        $this->dispatcher->addSubscriber($subscriber);
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
        return 'https://github.com/biblibre/kanboard-plugin-Mantis';
    }
}

