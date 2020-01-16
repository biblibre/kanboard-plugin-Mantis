<?php

namespace Kanboard\Plugin\Mantis\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Plugin\Mantis\ExternalTask\MantisTaskProvider;

class Mantis extends BaseController {
    public function show () {
        $task = $this->getTask();
        if ($task['external_provider'] != 'Mantis' || !$task['external_uri']) {
            return $this->response->json(array('error' => 'not a mantis task'));
        }

        $provider = new MantisTaskProvider($this->container);
        $mantisTask = $provider->fetch($task['external_uri'], $task['project_id']);
        if (!$mantisTask) {
            return $this->response->json(array('error' => 'mantis issue not found'));
        }

        $mantisIssue = $mantisTask->getIssue();
        $response = array(
            'status' => $mantisIssue->status->name,
        );

        $this->response->json($response);
    }
}
