<?php

namespace PM\InicioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	$defaultData = array(); 
    	$defaultData['nombre'] = date('Y-m-d--H:i');
    	$request = $this->get('request');
    	
    	$form = $this->createFormBuilder($defaultData)
    	->add('nombre', 'text')
    	->getForm();

    	if ($request->getMethod() == 'POST'){
    		$form->bind($request);
    		$data = $form->getData();
    		$filen = $data['nombre'].'.sql';
    		$filename = $this->backupDatabase('/home/david/backups/pirotecnia', $filen);
	    	$response = new Response();
//echo "222"; die;
	    	$response->setContent(file_get_contents($filename));
	    	$response->headers->set('Content-Type', 'application/octet-stream');

	    	$response->headers->set('Content-type', mime_content_type($filename));
	    	//$response->headers->set('Content-Disposition', 'inline; filename="Espectaculo.pdf"');
	    	$response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '"');
	    	$response->headers->set('Content-Type', 'application/force-download');
	    	//$response->sendHeaders();
	    	return $response;
    		
    	}
    	$response = $this->render('PMInicioBundle:Default:index.html.twig', array('form' => $form->createView()));
    	 
    	return $response;
    }
    
    public function backupDatabase($targetDirectory, $fileName = null)
    {
    	if (!is_dir($targetDirectory)) {
    		throw new \InvalidArgumentException(sprintf('Directory "%s" is not valid or it doesn\'t exist.', $targetDirectory));
    	}
    
    	if ($fileName !== null && !is_string($fileName)) {
    		throw new \InvalidArgumentException(sprintf('File name "%s" is not valid or it doesn\'t exist.', $targetDirectory));
    	}
    	$em = $this->getDoctrine()->getManager();
    	$connection        = $em->getConnection();
    	$backupFilename        = is_null($fileName) ? 'backup-mysql-'.strtolower(str_replace('_', '-', $connection->getDatabase())).'-'.date( 'Y-m-d-H-i-s' ).'.sql' : $fileName;
    	$backupFilePath        = $targetDirectory.DIRECTORY_SEPARATOR.$backupFilename;
    
    	$this->callVendorBackupTool($backupFilePath);
    
    	return $backupFilePath;
    }
    
    protected function callVendorBackupTool($backupFilePath)
    {
    	if (!$this->doCallVendorBackupTool($backupFilePath)) {
			ld('doCallVendorBackupTool() in DefaultController.php');
    	}
    }
    
    protected function doCallVendorBackupTool($backupFilePath)
    {
    	$em = $this->getDoctrine()->getManager();
    	$connection        = $em->getConnection();
    	$returnValue = '';
    	$output = array();
    	exec('rm -rf /home/david/backups/pirotecnia/*',$ou2);
    	$returnLine = exec(sprintf('mysqldump --opt --single-transaction --user="%s" --password="%s" --host="%s" --port="%s" %s > %s',
    			$connection->getUsername(),
    			$connection->getPassword(),
    			$connection->getHost(),
    			$connection->getPort(),
    			$connection->getDatabase(), $backupFilePath), $output, $returnValue);
    	if ($returnValue !== 0) {
    		return false;
    	} else {
    		return true;
    	}
    }
}
