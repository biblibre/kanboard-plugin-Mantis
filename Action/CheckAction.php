<?php

namespace Kanboard\Plugin\Mantis\Action;

use Kanboard\Action\Base;
use Kanboard\Model\TaskModel;

class CheckAction extends Base
{
    public function getDescription()
    {
        return 'Check Mantis issues for changes';
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
                $taskEventJob = $this->taskEventJob->withParams($task['id'], array(TaskModel::EVENT_UPDATE));
                $this->queueManager->push($taskEventJob);

                $this->taskMetadataModel->save($task['id'], array(
                    'mantis_last_sync' => date('c'),
                ));

                $this->logger->info(sprintf('Mantis issue %d was updated (task #%d)', $task['reference'], $task['id']));
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
