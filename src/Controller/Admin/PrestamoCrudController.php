<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Prestamo;
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
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Factory\FilterFactory;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PrestamoCrudController extends AbstractCrudController
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
        $user = $this->getUser();

        if(!$user instanceof User)
        {
            throw new \LogicException('Currently logged in user is not an instance of User!');
        }

        $prestamo = new Prestamo();
        $prestamo->setCarga($user->getUsername());

        return $prestamo;
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
            ->add(Crud::PAGE_INDEX, $exportAction);
    }

    public function export(AdminContext $context, CsvExporter $csvExporter)
    {
        $fields = FieldCollection::new($this->configureFields(Crud::PAGE_INDEX));
        $filters = $this->container->get(FilterFactory::class)->create($context->getCrud()->getFiltersConfig(), $fields, $context->getEntity());
        $queryBuilder = $this->createIndexQueryBuilder($context->getSearch(), $context->getEntity(), $fields, $filters);

        return $csvExporter->createResponseFromQueryBuilder(
            $queryBuilder,
            $fields,
            'prestamos.csv'
        );
    }
}
