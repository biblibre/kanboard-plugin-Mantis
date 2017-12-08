<?php

namespace Kanboard\Plugin\Mantis\Subscriber;

use Kanboard\Event\GenericEvent;
use Kanboard\Model\TaskLinkModel;
use Kanboard\Model\TaskModel;
use Kanboard\Model\CommentModel;
use Kanboard\Model\SubtaskModel;
use Kanboard\Model\TaskFileModel;
use Kanboard\Subscriber\BaseSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MantisSubscriber extends BaseSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
            TaskModel::EVENT_CREATE => 'handleEvent',
        );
    }

    public function handleEvent(GenericEvent $event, $eventName)
    {
        $task = $event['task'];
        if ($task['external_provider'] === 'Mantis') {
            $this->taskMetadataModel->save($task['id'], array(
                'mantis_last_sync' => date('c'),
            ));

            $this->taskExternalLinkModel->create(array(
                'task_id' => $task['id'],
                'url' => $task['external_uri'],
                'link_type' => 'weblink',
                'dependency' => 'related',
                'title' => sprintf('MT %s', $task['reference']),
            ));
        }
    }
}
