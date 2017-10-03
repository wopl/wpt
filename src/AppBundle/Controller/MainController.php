<?php
// **********************************************************************************
// **                                                                              **
// ** MainController.php                            (c) Wolfram Plettscher 10/2016 **
// **                                                                              **
// **********************************************************************************
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use AppBundle\Entity\User2Project;
use AppBundle\Form\ProjectType;

// **********************************************************************************
// **                                                                              **
// ** class: MainController                                                        **
// **                                                                              **
// **********************************************************************************
class MainController extends Controller
{

  // ********************************************************************************
  // **                                                                            **
  // ** function: indexAction                                                      **
  // **                                                                            **
  // ********************************************************************************
  /**
   * @Route("/", name="homepage")
   * @Security("has_role('ROLE_USER')")
   */
  public function indexAction(Request $request)
  {
    $session = new Session();

    $this->denyAccessUnlessGranted ('ROLE_USER', null, 'Insufficient privileges to access this page!');

    return $this->render ('home.html.twig');
  }
  
  // ********************************************************************************
  // **                                                                            **
  // ** function: impressumAction                                                  **
  // **                                                                            **
  // ********************************************************************************
//  /**
//   * @Route("/impressum", name="impressum")
//   */
//  public function impressumAction(Request $request)
//  {
//    return $this->render ('impressum.html.twig');
//  }


}
