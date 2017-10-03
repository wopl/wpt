<?php
// **********************************************************************************
// **                                                                              **
// ** Project.php                                   (c) Wolfram Plettscher 11/2016 **
// **                                                                              **
// **********************************************************************************
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use \PDO;
use AppBundle\Entity\Database;
use Symfony\Component\HttpFoundation\Session\Session;

// **********************************************************************************
// **                                                                              **
// ** class: Project                                                               **
// **                                                                              **
// **********************************************************************************
class Project 
{

  // ******************************************************************************** 
  // *** define all database/form fields
  protected $proj_uuid;
  
  /**
   * @Assert\NotBlank()
   */
  protected $projshort;
  protected $projlong;

  // ********************************************************************************
  // **                                                                            **
  // ** function: selectByUuid                                                     **
  // **                                                                            **
  // ********************************************************************************
  public function selectByUuid ($uuid)
  {
    $session = new Session();

    try {
      $this->db = new Database();
    } catch (PDOException $pe) {
      die ("Could not Instantiate Database: " . $pe->getMessage());
    }

	// *** select project by uuid 
    $query = $this->db->query ("SELECT projshort, projlong
      FROM project
      WHERE acc_uuid = :acc_uuid
      AND   proj_uuid = :proj_uuid
      ");
    $this->db->bind (':acc_uuid', $session->get ('acc_uuid'), PDO::PARAM_STR);
    $this->db->bind (':proj_uuid', $uuid, PDO::PARAM_STR);

	$row = $this->db->single();
    $count = $this->db->rowCount();

    if ($count < 1) 
      // we did not find any project for the user; credentials failed; return to login
      return (false);

  	$this->proj_uuid = $uuid;
	$this->projshort = $row['projshort'];
	$this->projlong = $row['projlong'];

    return (true);
  }
  
  // ********************************************************************************
  // **                                                                            **
  // ** functions: getProj_uuid / setProj_uuid                                     **
  // **                                                                            **
  // ********************************************************************************
  public function getProjuuid ()
  {
    return $this->proj_uuid;
  }

  public function setProjuuid ($proj_uuid)
  {
    $this->proj_uuid = $proj_uuid;
  }

  // ********************************************************************************
  // **                                                                            **
  // ** functions: getProjshort / setProjshort                                     **
  // **                                                                            **
  // ********************************************************************************
  public function getProjshort ()
  {
    return $this->projshort;
  }

  public function setProjshort ($projshort)
  {
    $this->projshort = $projshort;
  }

  // ********************************************************************************
  // **                                                                            **
  // ** functions: getProjlong / setProjlong                                       **
  // **                                                                            **
  // ********************************************************************************
  public function getProjlong ()
  {
    return $this->projlong;
  }

  public function setProjlong ($projlong)
  {
    $this->projlong = $projlong;
  }

}
