<?php

namespace App\Controller\Admin;

use App\Entity\Servicio;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ServicioCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Servicio::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
