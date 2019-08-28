<?php

namespace Kanboard\Plugin\Mantis\ExternalTask;

use Kanboard\Core\Base;
use Kanboard\Core\ExternalTask\ExternalTaskProviderInterface;
use Kanboard\Core\ExternalTask\NotFoundException;
use SoapClient;

class MantisTaskProvider extends Base implements ExternalTaskProviderInterface
{
    protected $soapClient;

    public function getName()
    {
        return 'Mantis';
    }

    public function getIcon()
    {
        return '<i class="fa fa-bug fa-fw"></i>';
    }

    public function getMenuAddLabel()
    {
        return t('Add a new Mantis issue');
    }

    public function fetch($uri, $project_id)
    {
        $issue = $this->getMantisIssue($uri);
        if (empty($issue)) {
            throw new NotFoundException('Mantis issue not found');
        }

        return new MantisTask($uri, $issue);
    }

    public function save($uri, array $formValues, array &$formErrors)
    {
        return true;
    }

    public function getImportFormTemplate()
    {
        return 'Mantis:task/import';
    }

    public function getCreationFormTemplate()
    {
        return 'Mantis:task/creation';
    }

    public function getModificationFormTemplate()
    {
        return 'Mantis:task/modification';
    }

    public function getViewTemplate()
    {
        return 'Mantis:task/view';
    }

    public function buildTaskUri(array $formValues)
    {
        return $this->getBaseUrl() . '/view.php?id=' . $formValues['id'];
    }

    protected function getSoapClient()
    {
        if (!isset($this->soapClient)) {
            $this->soapClient = new SoapClient($this->getWdslUri());
        }

        return $this->soapClient;
    }

    protected function getWdslUri()
    {
        return $this->getBaseUrl() . '/api/soap/mantisconnect.php?wsdl';
    }

    protected function getBaseUrl()
    {
        return $this->configModel->get('mantis_url');
    }

    protected function getMantisIssue($uri)
    {
        $matches = array();
        if (preg_match('/id=(\d+)$/', $uri, $matches)) {
            $id = $matches[1];
            try {
                $issue = $this->getSoapClient()->__soapCall('mc_issue_get', array(
                    'username' => $this->configModel->get('mantis_username'),
                    'password' => $this->configModel->get('mantis_password'),
                    'issue_id' => $id,
                ));

                return $issue;
            } catch (\Exception $e) {
                $this->logger->error('SOAP request failed : ' . $e->getMessage());
            }
        }
    }
}
