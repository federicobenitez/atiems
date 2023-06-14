<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Reparacion;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReparacionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reparacion::class;
    }


    public function configureFields(string $pageName): iterable
    {
        $user = $this->getUser();

        yield IdField::new('id')
            ->onlyOnIndex();
        yield TextField::new('nro_orden_manual')
            ->setLabel('N° Actuación');
        yield ChoiceField::new('estado')
            ->setChoices([
                'SIN REVISAR' => 'sin revisar',
                'EN REPARACIÓN' => 'en reparacion',
                'REPARADO' => 'reparado'
            ])
            ->allowMultipleChoices(false)
            ->renderExpanded();
        yield TextField::new('departamento_area')
            ->setRequired(true)
            ->setLabel('Departamento/Area');
        yield TextField::new('agente')
            ->setLabel('Pedido por');
        yield TextField::new('equipo');
        yield ChoiceField::new(('asignado_a'))
                ->setChoices([
                    'Barreiro Walter Gabriel' => 'Barreiro Walter Gabriel',
                    'Benítez Jorge Federico' =>  'Benítez Jorge Federico',
                    'Bogarin Daniel Elías' => 'Bogarin Daniel Elías',
                    'Dávila Mauro Alejandro' => 'Dávila Mauro Alejandro',
                    'Gomez Diego Humberto' => 'Gomez Diego Humberto',
                    'Leme, Héctor Ariel' => 'Leme, Héctor Ariel',
                    'Leva Cesar Eduardo' => 'Leva Cesar Eduardo',
                    'Muller Agustín' => 'Muller Agustín',
                    'Sánchez Alejandro Gabriel' => 'Sánchez Alejandro Gabriel'
                ])
                ->hideOnIndex();
        yield ChoiceField::new('notificado')
            ->setChoices([
                'NO' => 'no',
                'SI' => 'si'
            ])
            ->allowMultipleChoices(false)
            ->renderExpanded();
        yield DateField::new('fecha_inicio')
            ->hideOnIndex();
        yield DateField::new('fecha_fin')
            ->hideOnIndex();
        yield DateField::new('createdAt')
            ->hideOnIndex()
            ->setDisabled(true);
        yield TextField::new('carga')
            
            ->hideOnIndex()
            //->hideOnForm()
            ->setDisabled(true);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return parent::configureFilters($filters)
            ->add(ChoiceFilter::new('asignadoA')
                ->setLabel('Asignado A')
                ->setChoices([
                    'Barreiro Walter Gabriel' => 'Barreiro Walter Gabriel',
                    'Benítez Jorge Federico' =>  'Benítez Jorge Federico',
                    'Bogarin Daniel Elías' => 'Bogarin Daniel Elías',
                    'Dávila Mauro Alejandro' => 'Dávila Mauro Alejandro',
                    'Gomez Diego Humberto' => 'Gomez Diego Humberto',
                    'Leme, Héctor Ariel' => 'Leme, Héctor Ariel',
                    'Leva Cesar Eduardo' => 'Leva Cesar Eduardo',
                    'Muller Agustín' => 'Muller Agustín',
                    'Sánchez Alejandro Gabriel' => 'Sánchez Alejandro Gabriel'
                ]))
            ->add('fechaInicio')
            ->add('fechaFin')
            ->add(ChoiceFilter::new('estado')
                ->setChoices([
                    'SIN REVISAR' => 'sin revisar',
                    'EN REPARACIÓN' => 'en reparacion',
                    'REPARADO' => 'reparado'
                ]))
            ->add(ChoiceFilter::new('notificado')
                ->setChoices([
                    'NO' => 'no',
                    'SI' => 'si'
                ]));
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
        $reparacion = new Reparacion();
        $reparacion->setCarga($this->getUser()->getUsername());

        return $reparacion;
    }

}
