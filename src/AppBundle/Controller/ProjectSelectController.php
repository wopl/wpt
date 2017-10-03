<?php
// **********************************************************************************
// **                                                                              **
// ** ProjectSelectController.php                   (c) Wolfram Plettscher 11/2016 **
// **                                                                              **
// **********************************************************************************
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Entity\User2Project;
use AppBundle\Entity\Project;
//use AppBundle\Entity\ProjectUuids;
use AppBundle\Form\User2ProjectType;
//use AppBundle\Entity\Radio1;

// **********************************************************************************
// **                                                                              **
// ** class: ProjectSelectController                                               **
// **                                                                              **
// **********************************************************************************
class ProjectSelectController extends Controller
{

  // ********************************************************************************
  // **                                                                            **
  // ** function: projselAction                                                    **
  // **                                                                            **
  // ********************************************************************************
  /**
   * @Route("/projsel", name="projsel")
   * @Security("has_role('ROLE_USER')")
   */
  public function projselAction(Request $request)
  {
    $session = new Session();

    // select list of projects from database
    $usr2prj = new User2Project();
    $usr2prj->selectByUser();
    $dbresult = $usr2prj->getResult();
    $count = count ($dbresult);
	
    // ******************************************************************************
    // *** transfer db-results into form-entities
    $options = array (
      'project_uuids' => array(),
	  'defaultproject' => '',
      'selectproject' => ''
	);

    // prepare forms to show datatable of all selected projects
	for ($i=0; $i<$count; $i++)
	{
      // prepare to show each project in a line (proj_uuid, projshort)
      $oneproject = new Project();
      $oneproject->setProjuuid ($dbresult[$i]['proj_uuid']);
	  $oneproject->setProjshort ($dbresult[$i]['projlong']);
      $usr2prj->getProjects()->add ($oneproject);
	  
      // prepare to show the radio-buttons to define default project
	  // radio-buttons identify project by uuid
      $options ['project_uuids'][$i] = $dbresult[$i]['proj_uuid'];

      if ($dbresult[$i]['defaultproj']) 
        $options ['defaultproject'] = $dbresult[$i]['proj_uuid'];

      if ($dbresult[$i]['proj_uuid'] == $session->get ('projcurr')) 
        $options ['selectproject'] = $dbresult[$i]['proj_uuid'];
	}
	
	$form = $this->createForm (User2ProjectType::class, $usr2prj, $options);

    // ******************************************************************************
    // *** handle all form submission here
	$form->handleRequest ($request);
	if ($form->isSubmitted() && $form->isValid())
	{
      if ($form->get('submit')->isClicked()) {
        $formdata = $form->getdata();

        // update Default Project to database
        $usr2prj->updateDefault($formdata->getRadioDefaultproj());

        // if selected project has changed, get project-names from database
        if (($session->get ('projcurr')) != ($formdata->getRadioSelectproj()))
        {
          $project = new Project;
          $project->selectByUuid ($formdata->getRadioSelectproj());
          $session->set ('projcurr', $project->getProjuuid());
          $session->set ('projshort', $project->getProjshort());
          $session->set ('projlong', $project->getProjlong());
        }
		  
	  } elseif ($form->get('editgroups')->isClicked()) {
print_r ('editgroups');
      }
// perform action: save project to the database
//	  return $this->redirectToRoute ('homepage');
	}
	  
    // ******************************************************************************
    // *** call template with form-arguments
    return $this->render (
	  'projsel.html.twig',
	  array ('form' => $form->createView())
    );
  }

}
