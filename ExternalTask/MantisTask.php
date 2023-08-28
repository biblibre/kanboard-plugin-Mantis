<?php

namespace Kanboard\Plugin\Mantis\ExternalTask;

use Kanboard\Core\ExternalTask\ExternalTaskInterface;

class MantisTask implements ExternalTaskInterface
{
    protected $uri;
    protected $issue;

    public function __construct($uri, $issue)
    {
        $this->uri = $uri;
        $this->issue = $issue;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getIssueId()
    {
        return $this->issue->id;
    }

    public function getIssue()
    {
        return $this->issue;
    }

    public function getFormValues()
    {
        $t_id = ( $this->issue->flavor ? $this->issue->flavor . '-' : '') . sprintf( 'MT%d', $this->issue->id );
        $title = $t_id . sprintf(' %s [%s]', 
            $this->issue->summary, $this->issue->project->name);
        return array(
            'title' => $title,
            'description' => $this->issue->description,
            'reference' => $t_id,
        );
    }
}
