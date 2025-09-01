<?php

namespace App\Controller\Admin;

use App\Dto\Setting\Setting;
use App\Entity\Customer\Customer;
use App\Entity\Order\Order;
use App\Entity\Product\Product;
use App\Service\Setting\SettingService;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\ColorScheme;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use function Symfony\Component\Translation\t;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    private Setting $setting;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(private readonly SettingService $settingService)
    {
        $this->setting = $this->settingService->getSetting();
    }

    public function index(): Response
    {
        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle($this->setting->get('company_name'))
            ->setFaviconPath('favicon.svg')
            ->setDefaultColorScheme(ColorScheme::LIGHT)
            ;
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->addMenuItems([
                MenuItem::linkToRoute(t('Settings'), 'fas fa-cog', 'admin_setting'),
                MenuItem::linkToLogout(t('Logout'), 'fa fa-sign-out'),
            ])
            ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield  MenuItem::section(t('CRM'));
        yield MenuItem::subMenu(t('Customers'), 'fas fa-users')
            ->setSubItems([
                MenuItem::linkToCrud(t('List'), 'fas fa-list', Customer::class),
                MenuItem::linkToCrud(t('Create'), 'fas fa-plus', Customer::class)
                    ->setAction(Crud::PAGE_NEW),
            ]);

//        yield  MenuItem::section('Sales');
//        yield MenuItem::linkToCrud('Orders', 'fas fa-shopping-cart', Order::class);
//
//        yield  MenuItem::section('Inventory');
//        yield MenuItem::linkToCrud('Products', 'fas fa-box', Product::class);


        yield  MenuItem::section(t('Settings'));
        yield MenuItem::linkToRoute(t('Settings'), 'fas fa-cog', 'admin_setting');
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud()
            ->setPaginatorPageSize(30)
            ->setTimezone($this->setting->get('time_zone'))
            ->setDateTimeFormat('Y-m-d H:i:s')
            //sprintf('%s %s', $this->setting->get('date_format'), $this->setting->get('time_format'))
            ;
    }
}
