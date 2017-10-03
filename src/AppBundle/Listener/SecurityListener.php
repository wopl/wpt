<?php
// **********************************************************************************
// **                                                                              **
// ** SecurityListener.php                          (c) Wolfram Plettscher 03/2016 **
// **                                                                              **
// **********************************************************************************
namespace AppBundle\Listener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\Session\Session;

// **********************************************************************************
// **                                                                              **
// ** class: SecurityListener                                                      **
// **                                                                              **
// **********************************************************************************
class SecurityListener
{
//  protected $container;
	
  // ********************************************************************************
  // **                                                                            **
  // ** function: __construct                                                      **
  // **                                                                            **
  // ********************************************************************************
//  public function __construct(\Symfony\Component\DependencyInjection\Container $container)
  public function __construct()
  {
//    $this->container = $container;
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: onSecurityInteractiveLogin                                       **
  // **                                                                            **
  // ********************************************************************************
  public function onSecurityInteractiveLogin (InteractiveLoginEvent $event)
  {
    // this method runs after successful login
	// and will initiate parameters to access database and others

    // prepare the session
//    $session = new Session();

//	$host = $session->get ('database_host');
//    die ("$host");
	
    // set timezone (final solution not clear yet) ... by user?
//    date_default_timezone_set ('Europe/Berlin');
  }
}
