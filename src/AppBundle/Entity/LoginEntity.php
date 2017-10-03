<?php
// **********************************************************************************
// **                                                                              **
// ** LoginEntity.php                               (c) Wolfram Plettscher 11/2016 **
// **                                                                              **
// **********************************************************************************
namespace AppBundle\Entity;

use AppBundle\Entity\Database;
use Symfony\Component\HttpFoundation\Session\Session;
use \PDO;

// **********************************************************************************
// **                                                                              **
// ** class: LoginEntity                                                           **
// **                                                                              **
// **********************************************************************************
class LoginEntity 
{
  protected $db;
  protected $acc_name;
  protected $acc_company;
  protected $acc_uuid;
  protected $user_uuid;
  protected $user_role;
  protected $password;
  protected $firstname;
  protected $lastname;
  protected $proj_uuid;
  protected $projshort;
  protected $projlong;
  
  // ********************************************************************************
  // **                                                                            **
  // ** function: __construct                                                      **
  // **                                                                            **
  // ********************************************************************************
  public function __construct ()
  {
    try {
      $this->db = new Database();
    } catch (PDOException $pe) {
      die ("Could not Instantiate Login Database: " . $pe->getMessage());
    }
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: accountValid                                                     **
  // **                                                                            **
  // ********************************************************************************
  public function accountValid ($account)
  {
    // Query database for account (company name)
    $query = $this->db->query ("SELECT acc_uuid, inv_company
      FROM account
      WHERE acc_name = :acc_name
	  AND   active = '1'
      ");
    $this->db->bind (':acc_name', $account, PDO::PARAM_STR);

    $row = $this->db->single();
    $count = $this->db->rowCount();

    if ($count < 1) 
      // we did not find the account; credentials failed; return to login
      return (false);

    $this->acc_name = $account;
	$this->acc_company = $row['inv_company'];
	$this->acc_uuid = $row['acc_uuid'];

    return (true);
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: userValid                                                        **
  // **                                                                            **
  // ********************************************************************************
  public function userValid ($user)
  {
    // Query database for username
    $query = $this->db->query ("SELECT user_uuid, user_role, password, firstname, lastname
      FROM user
      WHERE acc_uuid = :acc_uuid
	  AND   user = :user
      ");
    $this->db->bind (':acc_uuid', $this->acc_uuid, PDO::PARAM_STR);
    $this->db->bind (':user', $user, PDO::PARAM_STR);

	$row = $this->db->single();
    $count = $this->db->rowCount();

    if ($count < 1) 
      // we did not find the user; credentials failed; return to login
      return (false);

	$this->user_uuid = $row['user_uuid'];
	$this->user_role = $row['user_role'];
	$this->password = $row['password'];
	$this->firstname = $row['firstname'];
	$this->lastname = $row['lastname'];

    return (true);
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: selectProject                                                    **
  // **                                                                            **
  // ********************************************************************************
  public function selectProject ()
  {
	// *** select the current project for this user 
    $query = $this->db->query ("SELECT u.proj_uuid, u.projshort, p.projlong
      FROM user2proj u
      INNER JOIN project p
      ON u.proj_uuid = p.proj_uuid
      WHERE u.acc_uuid = :acc_uuid
      AND   u.user_uuid = :user_uuid
      ORDER BY u.defaultproj DESC, u.projshort ASC
      ");
    $this->db->bind (':acc_uuid', $this->acc_uuid, PDO::PARAM_STR);
    $this->db->bind (':user_uuid', $this->user_uuid, PDO::PARAM_STR);

	$row = $this->db->single();
    $count = $this->db->rowCount();

    if ($count < 1) 
      // we did not find any project for the user; credentials failed; return to login
      return (false);

  	$this->proj_uuid = $row['proj_uuid'];
	$this->projshort = $row['projshort'];
	$this->projlong = $row['projlong'];

    return (true);
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: getUserData                                                      **
  // **                                                                            **
  // ********************************************************************************
  public function getUserData ()
  {
    $userData = [
      "acc_name" => $this->acc_name,
      "acc_company" => $this->acc_company,
      "acc_uuid" => $this->acc_uuid,
      "user_uuid" => $this->user_uuid,
      "user_role" => $this->user_role,
      "password" => $this->password,
      "firstname" => $this->firstname,
      "lastname" => $this->lastname,
	  "proj_uuid" => $this->proj_uuid,
	  "projshort" => $this->projshort,
	  "projlong" => $this->projlong
	];
    return ($userData);
  }
}
