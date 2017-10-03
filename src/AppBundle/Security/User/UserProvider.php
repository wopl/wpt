<?php
// **********************************************************************************
// **                                                                              **
// ** UserProvider.php                              (c) Wolfram Plettscher 11/2016 **
// **                                                                              **
// **********************************************************************************
namespace AppBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\LoginEntity;
use Symfony\Component\HttpFoundation\Session\Session;

// **********************************************************************************
// **                                                                              **
// ** class: UserProvider                                                          **
// **                                                                              **
// **********************************************************************************
class UserProvider implements UserProviderInterface
{

  // ********************************************************************************
  // **                                                                            **
  // ** function: loadUserByUsername                                               **
  // **                                                                            **
  // ********************************************************************************
  public function loadUserByUsername ($username)
  {

    // create $request to access gobal POST variables
	$request = Request::createFromGlobals();

	$session = new Session();
	
	// receive account value from login-form; otherwise $account "should" be empty
	// need to verify, this is the best approach
    $account = $request->request->get('account');

    // seems that this method is called twice; therefore this odd account setting procedure
	if ($account != "")
	{
      // account has been provided, therefore store it into session
      $session->set ('account_name', $account);
    } else {
	  // no account in GET/POST. Therefore we take it from session
	  $account = $session->get ('account_name');
	}

	// Access database
	$myLogin = new LoginEntity();

    // check database for valid account-id
    if ($myLogin->accountValid($account)) {
      // now check database for valid user-id
	  if ($myLogin->userValid($username)) {

        // finally check, if there is a project selected already
        if ($session->get ('projcurr') == '') {
          //no project, so we select the default project
          $myLogin->selectProject();
        }

        // get all userData collected so far
        $userData = $myLogin->getUserData();

        // Even we haven't verified the password, we start to fill session-variables
		// (after login we do not have proper access to them and would need to read database again)
        $session->set ('acc_uuid', $userData['acc_uuid']);
        $session->set ('company', $userData['acc_company']);
        $welcome = $userData['firstname'];
        $session->set ('username', $welcome);
		$session->set ('user_uuid', $userData['user_uuid']);
		
        if ($session->get ('projcurr') == '') {
          // set actual project selection - only if none is existing
          $session->set ('projcurr', $userData['proj_uuid']);
          $session->set ('projshort', $userData['projshort']);
          $session->set ('projlong', $userData['projlong']);
        }
	
        // for bcrypt-algorithm we do not need salt
		$password = $userData ['password'];
		$salt = '';
		
        $user_roles = [
		  $userData['user_role']
		];

		return new UserAuth (
          $username,
		  $password,
          $salt,
		  $user_roles
		);
      }	
	}	

    throw new UsernameNotFoundException(
      sprintf('Username "%s" does not exist.', $username)
    );

  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: refreshUser                                                      **
  // **                                                                            **
  // ********************************************************************************
  public function refreshUser (UserInterface $user)
  {
    if (!$user instanceof UserAuth) {
      throw new UnsupportedUserException (
        sprintf ('Instances of "%s" are not supported.', get_class ($user))
      );
    }
    return $this->loadUserByUsername ($user->getUsername());
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: supportsClass                                                    **
  // **                                                                            **
  // ********************************************************************************
  public function supportsClass ($class)
  {
    return $class === 'AppBundle\Security\User\UserAuth';
  }
}
