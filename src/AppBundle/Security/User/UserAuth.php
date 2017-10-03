<?php
// **********************************************************************************
// **                                                                              **
// ** UserAuth.php                                  (c) Wolfram Plettscher 03/2016 **
// **                                                                              **
// **********************************************************************************
namespace AppBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

// **********************************************************************************
// **                                                                              **
// ** class: User                                                                  **
// **                                                                              **
// **********************************************************************************
class UserAuth implements UserInterface, EquatableInterface
{

  private $username;
  private $password;
  private $salt;
  private $roles;

  // ********************************************************************************
  // **                                                                            **
  // ** function: __construct                                                      **
  // **                                                                            **
  // ********************************************************************************
  public function __construct ($username, $password, $salt, array $roles)
  {
    $this->username = $username;
    $this->password = $password;
    $this->salt = $salt;
    $this->roles = $roles;
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: getRoles                                                         **
  // **                                                                            **
  // ********************************************************************************
  public function getRoles ()
  {
    return $this->roles;
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: getPassword                                                      **
  // **                                                                            **
  // ********************************************************************************
  public function getPassword ()
  {
    return $this->password;
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: getSalt                                                          **
  // **                                                                            **
  // ********************************************************************************
  public function getSalt ()
  {
    return $this->salt;
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: getUsername                                                      **
  // **                                                                            **
  // ********************************************************************************
  public function getUsername ()
  {
    return $this->username;
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: eraseCredentials                                                 **
  // **                                                                            **
  // ********************************************************************************
  public function eraseCredentials ()
  {
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: isEqualTo                                                        **
  // **                                                                            **
  // ********************************************************************************
  public function isEqualTo (UserInterface $user)
  {
    if (!$user instanceof UserAuth) {
      return false;
    }

    if ($this->password !== $user->getPassword()) {
      return false;
    }

    if ($this->salt !== $user->getSalt()) {
      return false;
    }

    if ($this->username !== $user->getUsername()) {
      return false;
    }

    return true;
  }

}
