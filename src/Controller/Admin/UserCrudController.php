<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Form\FormBuilderInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $passwordEncoder;

    public function __construct( UserPasswordHasherInterface $passwordEncoder ) {

        $this->passwordEncoder = $passwordEncoder;
    }
    
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    //funcion para que solo el admin pueda administrar los usuarios
    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        if ($this->isGranted('ROLE_ADMIN')) {
            return $queryBuilder;
        }

        $user = $this->getUser();

        if(!$user instanceof User)
        {
            throw new \LogicException('Currently logged in user is not an instance of User!');
        }

        return $queryBuilder
            ->andWhere('entity.id = :id')
            ->setParameter('id', $user->getId());


    }

    
    public function configureFields(string $pageName): iterable
    {

        $roles = ['ROLE_ADMIN', 'ROLE_USER'];

        yield IdField::new('id')
            ->onlyOnIndex();
        yield TextField::new('username')
            ->setLabel('Nombre');
        yield EmailField::new('email');
        
        yield Field::new( 'password', 'New password' )->onlyWhenCreating()->setRequired( true )
                   ->setFormType( RepeatedType::class )
                   ->setFormTypeOptions( [
                       'type'            => PasswordType::class,
                       'first_options'   => [ 
                            'label' => 'Nueva contraseña',
                            'row_attr' => [
                                'class' => 'col-md-6',
                            ],
                        ],
                       'second_options'  => [ 
                            'label' => 'Repetir contraseña',
                            'row_attr' => [
                                'class' => 'col-md-6',
                            ],
                        ],
                       'error_bubbling'  => true,
                       'invalid_message' => 'Las contraseñas no coinciden.',
                   ]);
        yield Field::new( 'password', 'New password' )->onlyWhenUpdating()->setRequired( true )
            ->setFormType( RepeatedType::class )
            ->setFormTypeOptions( [
                'type'            => PasswordType::class,
                'first_options'   => [ 
                    'label' => 'Nueva contraseña',
                    'row_attr' => [
                        'class' => 'col-md-6',
                    ],
                ],
                'second_options'  => [ 
                    'label' => 'Repetir contraseña',
                    'row_attr' => [
                        'class' => 'col-md-6',
                    ],
                ],
                'error_bubbling'  => true,
                'invalid_message' => 'Las contraseñas no coinciden.',
            ]);
        yield TelephoneField::new('phoneNumber')
                ->setLabel('Teléfono');
        yield ChoiceField::new('roles')
            ->setChoices(array_combine($roles, $roles))
            ->allowMultipleChoices()
            ->renderExpanded()
            ->renderAsBadges();
        yield DateField::new('createdAt')
            ->setLabel('Creado')
            ->hideOnIndex()
            ->setDisabled(true);
        
    }

   public function createEditFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
   {
        $plainPassword = $entityDto->getInstance()?->getPassword();
        $formBuilder   = parent::createEditFormBuilder( $entityDto, $formOptions, $context );
        $this->addEncodePasswordEventListener( $formBuilder, $plainPassword );

        return $formBuilder;
   }

   public function createNewFormBuilder(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
   {
        $formBuilder = parent::createNewFormBuilder( $entityDto, $formOptions, $context );
        $this->addEncodePasswordEventListener( $formBuilder );

        return $formBuilder;
   }

   protected function addEncodePasswordEventListener( FormBuilderInterface $formBuilder, $plainPassword = null ): void {
    $formBuilder->addEventListener( FormEvents::SUBMIT, function ( FormEvent $event ) use ( $plainPassword ) {
        /** @var User $user */
        $user = $event->getData();
        if ( $user->getPassword() !== $plainPassword ) {
            $user->setPassword( $this->passwordEncoder->hashPassword( $user, $user->getPassword() ) );
        }
    } );
}

    
}
