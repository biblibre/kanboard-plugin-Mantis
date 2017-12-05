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
        return array(
            'title' => $this->issue->summary,
            'description' => $this->issue->description,
            'reference' => $this->issue->id,
        );
    }
}
