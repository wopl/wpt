<?php
// **********************************************************************************
// **                                                                              **
// ** SecurityController.php                        (c) Wolfram Plettscher 11/2016 **
// **                                                                              **
// **********************************************************************************
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;


// **********************************************************************************
// **                                                                              **
// ** class: SecurityController                                                    **
// **                                                                              **
// **********************************************************************************
class SecurityController extends Controller
{
  // ********************************************************************************
  // **                                                                            **
  // ** function: loginAction                                                      **
  // **                                                                            **
  // ********************************************************************************
  /**
   * @Route("/login", name="login")
   */
  public function loginAction(Request $request)
  {
    $session = new Session();
	
    // get database credentials from parameters.yml and put them into session
    // (parameters.yml is only available within controller; session everywhere)
    $session->set ('database_host', $this->container->getParameter ('database_host'));
    $session->set ('database_port', $this->container->getParameter ('database_port'));
    $session->set ('database_name', $this->container->getParameter ('database_name'));
    $session->set ('database_user', $this->container->getParameter ('database_user'));
    $session->set ('database_password', $this->container->getParameter ('database_password'));

    $authenticationUtils = $this->get('security.authentication_utils');

	// get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();
	
    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();

//???????????    $account = $request->request->get('account');

	return $this->render('login.html.twig',array(
      // last username entered by the user
      'last_username' => $lastUsername,
      'error' => $error,
      ));
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: impressumAction                                                  **
  // **                                                                            **
  // ********************************************************************************
  /**
   * @Route("/impressum", name="impressum")
   */
  public function impressumAction(Request $request)
  {
    return $this->render ('impressum.html.twig');
  }

}
