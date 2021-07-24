<?php

namespace App\Form;

use App\Entity\Rent;
use App\Form\DataTransformer\BookTransformer;
use App\Form\DataTransformer\MemberTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RentType extends AbstractType
{
    /**
     * @var BookTransformer
     */
    private $bookTransformer;

    /**
     * @var MemberTransformer
     */
    private $memberTransformer;


    public function __construct(BookTransformer $bookTransformer, MemberTransformer $memberTransformer)
    {
        $this->bookTransformer = $bookTransformer;
        $this->memberTransformer = $memberTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('book',TextType::class)
            ->add('member',TextType::class)
            ->add('fromDate',DateType::class)
            ->add('toDate',DateType::class)
        ;

        $builder
            ->get('book')->addModelTransformer($this->bookTransformer);

        $builder
            ->get('member')->addModelTransformer($this->memberTransformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rent::class,
        ]);
    }
}
