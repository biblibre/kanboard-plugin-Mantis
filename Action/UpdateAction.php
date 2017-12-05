<?php

namespace Kanboard\Plugin\Mantis\Action;

use Kanboard\Action\Base;

class UpdateAction extends Base
{
    public function getDescription()
    {
        return 'update Mantis tasks';
    }

    public function doAction(array $data)
    {
        $tasks = $this->taskFinderModel->getAll($this->getProjectId());
        $tasks = array_filter($tasks, function($task) {
            return $task['external_provider'] == 'Mantis';
        });
        $provider = $this->externalTaskManager->getProvider('Mantis');
        foreach ($tasks as $task) {
            $t = $provider->fetch($task['external_uri']);
            $last_sync = $this->taskMetadataModel->get($task['id'], 'mantis_last_sync', '0');
            $last_updated = $t->getIssue()->last_updated;
            if ($last_updated > $last_sync) {
                echo 'updated ' . $task['id'];
                //$this->commentModel->create(array('task_id' => $task['id'], 'comment' => 'Mantis issue was updated'));
                $this->queueManager->push($this->taskEventJob->withParams($task['id'], array(\Kanboard\Model\TaskModel::EVENT_UPDATE)));
                $this->taskMetadataModel->save($task['id'], array('mantis_last_sync' => $last_updated));
            }
        }
    }

    public function getActionRequiredParameters()
    {
        return array();
    }

    public function getEventRequiredParameters()
    {
        return array();
    }

    public function getCompatibleEvents()
    {
        return array('task.cronjob.daily');
    }

    public function hasRequiredCondition(array $data)
    {
        return true;
    }
}
