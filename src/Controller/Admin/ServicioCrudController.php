<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Servicio;
use App\Service\CsvExporter;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FilterFactory;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ServicioCrudController extends AbstractCrudController
{
    private AdminUrlGenerator $adminUrlGenerator;
    private RequestStack $requestStack;

    public function __construct(AdminUrlGenerator $adminUrlGenerator, RequestStack $requestStack)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->requestStack = $requestStack;
    }
    
    public static function getEntityFqcn(): string
    {
        return Servicio::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->onlyOnIndex();
        yield TextField::new('nro_orden_manual')
            ->setLabel('N°Actuación');
        yield ChoiceField::new('estado')
            ->setChoices([
                'SIN REVISAR' => 'sin revisar',
                'EN REPARACIÓN' => 'en reparacion',
                'REPARADO' => 'reparado'
            ])
            ->allowMultipleChoices(false)
            ->renderExpanded();
        yield TextField::new('departamento_area')
            ->setLabel('Departamento/Area');
        yield TextField::new('agente')
            ->setLabel('Pedido por');
        yield TextareaField::new('equipo')
            ->setLabel('Servicio');
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
        $user = $this->getUser();

        if(!$user instanceof User)
        {
            throw new \LogicException('Currently logged in user is not an instance of User!');
        }

        $servicio = new Servicio();
        $servicio->setCarga($user->getUsername());

        return $servicio;
    }

    public function configureActions(Actions $actions): Actions
    {
        $exportAction = Action::new(('exportar'))
            ->linkToUrl(function(){
                $request = $this->requestStack->getCurrentRequest();

                return $this->adminUrlGenerator->setAll($request->query->all())
                    ->setAction('export')
                    ->generateUrl();
            })
            ->createAsGlobalAction()
            ->linkToCrudAction('export')
            ->addCssClass('btn btn-success')
            ->setIcon('fa fa-download');

        return parent::configureActions($actions)
            ->add(Crud::PAGE_INDEX, $exportAction)
            ->setPermission(Action::DELETE, 'ROLE_ADMIN');
    }

    public function export(AdminContext $context, CsvExporter $csvExporter)
    {
        $fields = FieldCollection::new($this->configureFields(Crud::PAGE_INDEX));
        $filters = $this->container->get(FilterFactory::class)->create($context->getCrud()->getFiltersConfig(), $fields, $context->getEntity());
        $queryBuilder = $this->createIndexQueryBuilder($context->getSearch(), $context->getEntity(), $fields, $filters);

        return $csvExporter->createResponseFromQueryBuilder(
            $queryBuilder,
            $fields,
            'servicios.csv'
        );
    }
    
}
