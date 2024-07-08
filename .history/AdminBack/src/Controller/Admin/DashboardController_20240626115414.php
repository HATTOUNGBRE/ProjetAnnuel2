<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use App\Entity\Prestation;
use App\Entity\Prestataire;



use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Controller\Admin\PropertyCrudController;


use App\Entity\Category;
use App\Entity\CategoryUser;
use App\Entity\Property;
use App\Entity\User;

class DashboardController extends AbstractDashboardController
{
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(PropertyCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('AdminBack');
    }

    public function configureMenuItems(): iterable
    {
        // dump('configureMenuItems called');die;
        yield MenuItem::section('PCS Backend Admin');

        yield MenuItem::section('Property');

        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Add Property', 'fas fa-plus', Property::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Propertys', 'fas fa-eye', Property::class)
        ]);
        yield MenuItem::section('Categories Users / Propertys');
       
        yield MenuItem::subMenu('Users', 'fas fa-bars')->setSubItems([
                    MenuItem::linkToCrud('Create User Category ', 'fas fa-plus', CategoryUser::class)->setAction(Crud::PAGE_NEW),
                    MenuItem::linkToCrud('Show Categories ', 'fas fa-eye', CategoryUser::class)
                ]);

        yield MenuItem::subMenu('Propertys', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create Property Category ', 'fas fa-plus', Category::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Categories', 'fas fa-eye', Category::class)
        ]);
        
       

        yield MenuItem::section('Utilisateurs');

        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create User', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Users', 'fas fa-eye', User::class)
        ]);

       
        yield MenuItem::section('Prestations');
        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create Prestation', 'fas fa-plus', Prestation::class)->setAction
            (Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Prestations', 'fas fa-eye', Prestation::class)
        ]); 

        yield MenuItem::section('Prestataires');
        yield MenuItem::subMenu('Actions', 'fas fa-bars')->setSubItems([
            MenuItem::linkToCrud('Create Prestataire', 'fas fa-plus', Prestataire::class)->setAction
            (Crud::PAGE_NEW),
            MenuItem::linkToCrud('Show Prestataires', 'fas fa-eye', Prestataire::class)
        ]);

       

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
