<?php

namespace App\Controller\Admin;

use App\Entity\Prestamo;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PrestamoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Prestamo::class;
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
