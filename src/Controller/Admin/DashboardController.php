<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Prestamo;
use App\Entity\Servicio;
use App\Entity\Reparacion;
use App\Repository\ReparacionRepository;
use App\Repository\ServicioRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private ReparacionRepository $reparacionRepository;
    private ServicioRepository $servicioRepository;

    public function __construct(ReparacionRepository $reparacionRepository, ServicioRepository $servicioRepository)
    {
        $this->reparacionRepository = $reparacionRepository;
        $this->servicioRepository = $servicioRepository;
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $ultimasReparaciones = $this->reparacionRepository->findLatest();
        $ultimosServicios = $this->servicioRepository->findLatest();

        return $this->render('admin/index.html.twig',[
            'ultimasReparaciones' => $ultimasReparaciones,
            'ultimosServicios' => $ultimosServicios,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<center><img src="img/icono.png"></src></center>')
            ->setFaviconPath('img/icono.png');
    
    }

    public function configureMenuItems(): iterable
    {   
        //yield MenuItem::section('<hr>');
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-book');

        
        yield MenuItem::linkToCrud('Usuarios', 'fa fa-users', User::class)
            ->setPermission('ROLE_ADMIN');
        yield MenuItem::linkToCrud('Reparaciones', 'fa fa-wrench', Reparacion::class);
        yield MenuItem::linkToCrud('Servicios', 'fa fa-bell-concierge', Servicio::class);
        yield MenuItem::linkToCrud('Préstamos', 'fa fa-right-long', Prestamo::class);

        yield MenuItem::section('<hr>');
        yield MenuItem::linkToUrl('Salir', 'fa fa-right-from-bracket', $this->generateUrl('app_logout'));

    }

    public function configureActions(): Actions
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    
    public function configureAssets(): Assets
    {
        return parent::configureAssets();
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            ->setDefaultSort([
                'id' => 'DESC'
            ]);
    }
}
