<?php

namespace App\Controller\Admin;

use App\Entity\Reparacion;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReparacionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reparacion::class;
    }

/*
    public function configureFields(string $pageName): iterable
    {
        
        yield IdField::new('id');
        yield TextField::new('nro_orden_manual');
        yield TextField::new('agente');
        yield TextField::new('estado');
        yield TextField::new('departamento_area');

    }
    */
}
