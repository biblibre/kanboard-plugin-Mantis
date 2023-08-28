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
        $t_id = $this->splitID( $formValues['id'] );
        return $this->getBaseUrl( $t_id[0] ) . '/view.php?id=' . $t_id[1];
    }

    protected function getSoapClient(string $p_id = '')
    {
        if (!isset($this->soapClient)) {
            $this->soapClient = array();
        }
        $t_flavor = $this->idFlavor( $p_id );
        // error_log( 'getSoapClient id=' . $p_id . ' flavor=' . $t_flavor );
        if( !array_key_exists( $t_flavor, $this->soapClient ) ) {
            $this->soapClient[$t_flavor] = new SoapClient($this->getWsdlUri( $t_flavor ),
                array(
                    'trace' => true,
                ));
        }

        return $this->soapClient[$t_flavor];
    }

    protected function getWsdlUri(string $p_id = ''): string
    {
        return $this->getBaseUrl( $p_id ) . '/api/soap/mantisconnect.php?wsdl';
    }

    protected function getBaseUrl(string $p_id = ''): string
    {
        $i = strpos( $p_id, '/view.php?id=' );
        if( $i > 0 ) {
            return substr( $p_id, 0, $i );
        }
        $t_urls = $this->getURLs();
        $t_flavor = $this->idFlavor( $p_id );
        if( array_key_exists( $t_flavor, $t_urls ) ) {
            return $t_urls[$t_flavor];
        }
        return $t_urls[''];
    }

    private function getURLs(): array {
        $t_urls = $this->configModel->get('mantis_url');
        if( str_starts_with( $t_urls, '{' ) && str_ends_with( $t_urls, '}' ) ) {
            return json_decode( $t_urls, true );
        }
        return array( '' => $t_urls );
    }

    protected function getMantisIssue(string $uri)
    {
        $matches = array();
        if (preg_match('/id=(?:[A-Z]-?(?:MT)?)?(\d+)$/', $uri, $matches)) {
            $id = $matches[1];
            $t_flavor = $this->idFlavor( $uri );
            try {
                $issue = $this->getSoapClient( $t_flavor )->__soapCall('mc_issue_get', array(
                    'username' => $this->configModel->get('mantis_username'),
                    'password' => $this->configModel->get('mantis_password'),
                    'issue_id' => $id,
                ));
                $issue->flavor = $t_flavor;

                return $issue;
            } catch (\Exception $e) {
                $this->logger->error('SOAP request failed : ' . $e->getMessage());
                error_log('SOAP request failed: uri=' . var_export( $uri, TRUE ) . ' ERROR:' . $e->getMessage() . ' REQUEST: ' . $this->getSoapClient()->__getLastRequest() . ' RESPONSE: ' . $this->getSoapClient()->__getLastResponseHeaders());
            }
        }
    }

    private function idFlavor( string $p_id ): string {
        $i = strpos( $p_id, '/view.php?id=' );
        if( $i ) {  // URI
            $t_url = substr( $p_id, 0, $i );
            foreach( $this->getURLs() as $t_key => $t_value ) {
                if( $t_key != '' && $t_url == $t_value ) {
                    return $t_key;
                }
            }
            return '';
        }
        
        $t_flavor = substr( $p_id, 0, 1);
        if( ctype_alpha( $t_flavor ) ) {
            if( ctype_upper( $t_flavor ) ) {
                return $t_flavor;
            }
            return strtoupper( $t_flavor );
        }    
        return '';
    }
    
    private function splitID(string|array $p_id): array {
        if( is_array( $p_id) ) { 
            return $p_id;
        }
        $matches = array();
        if (preg_match('/^(?:([A-Za-z])-?(?:MT)?)?(\d+)$/', $p_id, $matches)) {
            return array( strtoupper( $matches[1] ), $matches[2] );
        }
        return array( '', $p_id );
    }

}
