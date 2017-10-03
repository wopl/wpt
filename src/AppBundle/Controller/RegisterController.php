<?php
// **********************************************************************************
// **                                                                              **
// ** RegisterController.php                        (c) Wolfram Plettscher 02/2016 **
// **                                                                              **
// **********************************************************************************
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// **********************************************************************************
// **                                                                              **
// ** class: RegisterController                                                    **
// **                                                                              **
// **********************************************************************************
class RegisterController extends Controller
{

  // ********************************************************************************
  // **                                                                            **
  // ** function: indexAction                                                      **
  // **                                                                            **
  // ********************************************************************************
  /**
   * @Route("/register", name="register")
   */
  public function indexAction(Request $request)
  {
    return $this->render ('register.html.twig');
  }
}
