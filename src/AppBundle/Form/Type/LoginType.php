<?php
// **********************************************************************************
// **                                                                              **
// ** LoginType.php                                 (c) Wolfram Plettscher 11/2016 **
// **                                                                              **
// **********************************************************************************
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

// **********************************************************************************
// **                                                                              **
// ** class: LoginType                                                             **
// **                                                                              **
// **********************************************************************************
class LoginType extends AbstractType
{

  // ********************************************************************************
  // **                                                                            **
  // ** function: buildForm                                                        **
  // **                                                                            **
  // ********************************************************************************
  public function buildForm (FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add ('account', TextType::class)
      ->add ('username', TextType::class)
      ->add ('password', TextType::class);
//      ->add ('save', SubmitType::class, array('label' => 'Login'));
  }

  // ********************************************************************************
  // **                                                                            **
  // ** function: configureOptions                                                 **
  // **                                                                            **
  // ********************************************************************************
  public function configureOptions (OptionsResolver $resolver)
  {
    $resolver->setDefaults (array (
      'data_class' => 'AppBundle\Entity\Login'
    ));
  }
}
