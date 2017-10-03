<?php
// **********************************************************************************
// **                                                                              **
// ** ProjectType.php                               (c) Wolfram Plettscher 11/2016 **
// **                                                                              **
// **********************************************************************************
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;

// **********************************************************************************
// **                                                                              **
// ** class: ProjectType                                                           **
// **                                                                              **
// **********************************************************************************
class ProjectType extends AbstractType
{

  // ********************************************************************************
  // **                                                                            **
  // ** function: buildForm                                                        **
  // **                                                                            **
  // ********************************************************************************
  public function buildForm (FormBuilderInterface $builder, array $options)
  {
    $builder
	  ->add ('proj_uuid', HiddenType::class)
      ->add ('projshort', HiddenType::class, array (
	    'label' => false,
	  ))
      ;
  }
  
  // ********************************************************************************
  // **                                                                            **
  // ** function: configureOptions                                                 **
  // **                                                                            **
  // ********************************************************************************
  public function configureOptions (OptionsResolver $resolver)
  {
    $resolver->setDefaults (array (
      'data_class' => 'AppBundle\Entity\Project',
	));  
  }
}
