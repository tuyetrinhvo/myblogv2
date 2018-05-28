<?php
/**
 * Class Doc Comment
 *
 * PHP version 7.0
 *
 * @category PHP_Class
 * @package  AppBundle
 * @author   trinhvo <ttvdep@gmail.com>
 * @license  License Name
 * @link     Link Name
 */
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class ContactType
 *
 * @category PHP_Class
 * @package  AppBundle\Form\Type
 * @author   trinhvo <ttvdep@gmail.com>
 * @license  License Name
 * @link     Link Name
 */
class ContactType extends AbstractType
{
    /**
     * Function buildForm Article
     *
     * @param FormBuilderInterface $builder Some argument description
     * @param array                $options Some argument description
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'nom',
                TextType::class,
                [
                'attr' => ['placeholder'=>'Votre nom'],
                'constraints' => [
                    new NotBlank(["message"=>"Remplissez votre nom, s'il vous plaît."])
                ]
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                'attr' => ['placeholder'=>'Votre adresse email'],
                'constraints' => [
                    new NotBlank(["message"=>"Remplissez votre adresse d'email, s'il vous plaît."]),
                    new Email(["message" =>"Votre adresse d'email n'est pas valide"])
                ]
                ]
            )
            ->add(
                'message',
                TextareaType::class,
                [
                'attr' => ['placeholder'=>'Votre message'],
                'constraints' => [
                    new NotBlank(["message"=>"Laissez votre message, s'il vous plaît."])
                ]
                ]
            );
    }

    /**
     * Function configureOptions
     *
     * @param OptionsResolver $resolver Some argument description
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'error_bubbling' => true,
            ]
        );
    }
}
