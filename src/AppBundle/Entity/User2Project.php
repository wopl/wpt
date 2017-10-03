<?php
// **********************************************************************************
// **                                                                              **
// ** User2Project.php                              (c) Wolfram Plettscher 11/2016 **
// **                                                                              **
// **********************************************************************************
namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use \PDO;
use AppBundle\Entity\Database;
use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\Common\Collections\ArrayCollection;

// **********************************************************************************
// **                                                                              **
// ** class: User2Project                                                          **
// **                                                                              **
// **********************************************************************************
class User2Project
{
  protected $db;
  protected $dbresult;

  // ******************************************************************************** 
  // *** define all database/form fields
  protected $projects;
  protected $radio_defaultproj;
  protected $radio_selectproj;
  
  // ********************************************************************************
  // **                                                                            **
  // ** function: __construct                                                      **
  // **                                                                            **
  // ********************************************************************************
  public function __construct ()
  {
    $this->projects = new ArrayCollection();
    
    try {
      $this->db = new Database();
    } catch (PDOException $pe) {
      die ("Could not Instantiate Database: " . $pe->getMessage());
    }
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: selectByUser                                                     **
  // **                                                                            **
  // ********************************************************************************
  public function selectByUser ()
  {
    $session = new Session();

    // Query database for all projects of account / user
    $query = $this->db->query ("SELECT u.proj_uuid, u.projshort, u.defaultproj, p.projlong
      FROM user2proj u
      INNER JOIN project p
      ON u.proj_uuid = p.proj_uuid
      WHERE u.acc_uuid = :acc_uuid
      AND u.user_uuid = :user_uuid
      ORDER BY p.projlong ASC
      ");

    $this->db->bind (':acc_uuid', $session->get ('acc_uuid'), PDO::PARAM_STR);
    $this->db->bind (':user_uuid', $session->get ('user_uuid'), PDO::PARAM_STR);
    $this->dbresult = $this->db->resultset();
    $count = $this->db->rowCount();

    if ($count < 1) 
      // we did not find any project
      return (false);

    // fill with any value to avoid violating the 'not empty' form-constraint
//    $this->projshort = $this->dbresult[0]['projshort'];
      
    return (true);
  }
  // ********************************************************************************
  // **                                                                            **
  // ** function: updateDefault                                                    **
  // **                                                                            **
  // ********************************************************************************
  public function updateDefault ($uuid)
  {
    $session = new Session();
//print_r ('update uuid ' . $uuid);   
//print_r ('<br> current project ' . $session->get ('user_uuid')); 

    // Step1: delete any default project for this user
    $this->db->query ("UPDATE user2proj
      SET defaultproj = '0'
      WHERE acc_uuid = :acc_uuid
      AND user_uuid = :user_uuid
      ");
    $this->db->bind (':acc_uuid', $session->get ('acc_uuid'), PDO::PARAM_STR);
    $this->db->bind (':user_uuid', $session->get ('user_uuid'), PDO::PARAM_STR);
    $this->db->execute();
  
    // Step2: set default project for this user given by form
    $this->db->query ("UPDATE user2proj
      SET defaultproj = '1'
      WHERE acc_uuid = :acc_uuid
      AND user_uuid = :user_uuid
      AND proj_uuid = :proj_uuid
      ");
    $this->db->bind (':acc_uuid', $session->get ('acc_uuid'), PDO::PARAM_STR);
    $this->db->bind (':user_uuid', $session->get ('user_uuid'), PDO::PARAM_STR);
    $this->db->bind (':proj_uuid', $uuid, PDO::PARAM_STR);
    $this->db->execute();
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: getResult                                                        **
  // **                                                                            **
  // ********************************************************************************
  public function getResult ()
  {
    return ($this->dbresult);
  }
  
  // ********************************************************************************
  // **                                                                            **
  // ** functions: getProjects                                                     **
  // **                                                                            **
  // ********************************************************************************
  public function getProjects ()
  {
    return $this->projects;
  }

  // ********************************************************************************
  // **                                                                            **
  // ** functions: getRadioDefaultproj / setRadioDefaultproj                       **
  // **                                                                            **
  // ********************************************************************************
  public function getRadioDefaultproj ()
  {
    return $this->radio_defaultproj;
  }
  public function setRadioDefaultproj ($uuid)
  {
    $this->radio_defaultproj = $uuid;
  }

  // ********************************************************************************
  // **                                                                            **
  // ** functions: getRadioSelectproj / setRadioSelectproj                       **
  // **                                                                            **
  // ********************************************************************************
  public function getRadioSelectproj ()
  {
    return $this->radio_selectproj;
  }
  public function setRadioSelectproj ($uuid)
  {
    $this->radio_selectproj = $uuid;
  }

}
