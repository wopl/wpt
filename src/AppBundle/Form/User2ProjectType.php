<?php
// **********************************************************************************
// **                                                                              **
// ** User2ProjectType.php                          (c) Wolfram Plettscher 11/2016 **
// **                                                                              **
// **********************************************************************************
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

// **********************************************************************************
// **                                                                              **
// ** class: User2ProjectType                                                      **
// **                                                                              **
// **********************************************************************************
class User2ProjectType extends AbstractType
{

  // ********************************************************************************
  // **                                                                            **
  // ** function: buildForm                                                        **
  // **                                                                            **
  // ********************************************************************************
  public function buildForm (FormBuilderInterface $builder, array $options)
  {
    // projects contains all read-only information per project line displayed 
    $builder->add ('projects', CollectionType::class, array(
	  'entry_type' => ProjectType::class,
	  'entry_options' => array ('label' => false),
	  'label' => false,
	  ));

    // add radio button to select the default-project
    // choices_defaultproject allows to identify a line by uuid
	$choices_defaultproject = array (
	  'choices' => $options['project_uuids'],
	  'expanded' => true,
	  'multiple' => false,
	  'data' => $options['defaultproject'],
      'choice_label' => false,
    );
	$builder->add ('radio_defaultproj', ChoiceType::class, $choices_defaultproject);
	
    // add radio button to select the current project
    // choices_selectproject allows to identify a line by uuid
	$choices_selectproject = array (
	  'choices' => $options['project_uuids'],
	  'expanded' => true,
	  'multiple' => false,
	  'data' => $options['selectproject'],
      'choice_label' => false,
    );
	$builder->add ('radio_selectproj', ChoiceType::class, $choices_selectproject);
	
    // finally add the submit buttons to the form
    $builder
      ->add ('submit', SubmitType::class, array ('label' => 'submit'))
//      ->add ('editgroups', SubmitType::class, array ('label' => 'edit groups'))
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
      'data_class' => 'AppBundle\Entity\User2Project',
	  'defaultproject' => '',
	  'selectproject' => '',
	  'project_uuids' => array(),
	));  
  }
}
