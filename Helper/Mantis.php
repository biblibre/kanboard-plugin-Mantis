<?php

namespace Kanboard\Plugin\Mantis\Helper;

use Kanboard\Core\Base;

class Mantis extends Base
{
    public function isMantisTask($task_id)
    {
        $task = $this->taskFinderModel->getById($task_id);

        return !empty($task['external_provider']) && $task['external_provider'] === 'Mantis';
    }
}
