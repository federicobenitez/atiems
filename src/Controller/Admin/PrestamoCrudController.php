<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Prestamo;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PrestamoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Prestamo::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->onlyOnIndex();
        yield TextField::new('agente')
            ->setLabel('Pedido por');
        yield TextField::new('departamento_area')
            ->setLabel('Departamento/Area');
        yield TextField::new('marca');
        yield TextField::new('modelo');
        yield ChoiceField::new('estado')
            ->setChoices([
                'FINALIZADO' => 'finalizado',
                'VIGENTE' => 'vigente',
            ])
            ->allowMultipleChoices(false)
            ->renderExpanded();
        yield TextField::new('carga')
            ->hideOnIndex();
        yield DateField::new('createdAt')
            ->setLabel('Creado')
            ->hideOnIndex()
            ->setDisabled(true);

    }

    public function configureFilters(Filters $filters): Filters
    {
        return parent::configureFilters($filters)
            ->add(TextFilter::new('agente'))
            ->add(TextFilter::new('departamentoArea'))
            ->add(ChoiceFilter::new('estado')
                ->setChoices([
                    'FINALIZADO' => 'finalizado',
                    'VIGENTE' => 'vigente',
                ]))
            ->add('createdAt');
    }
    
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $user = $this->getUser();

        if (!$user instanceof User){
            throw new \LogicException('Currently logged in user is not an instance of User!');
        }

        $entityInstance->setCarga($user->getUsername());
        
        parent::updateEntity($entityManager, $entityInstance);
    }

    public function createEntity(string $entityFqcn)
    {
        $prestamo = new Prestamo();
        $prestamo->setCarga($this->getUser()->getUsername());

        return $prestamo;
    }
}
