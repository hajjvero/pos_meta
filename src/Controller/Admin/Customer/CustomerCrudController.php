<?php

namespace App\Controller\Admin\Customer;

use App\Entity\Customer\Customer;
use App\Twig\Extension\Setting\FinancialExtension;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use function Symfony\Component\Translation\t;

class CustomerCrudController extends AbstractCrudController
{
    public function __construct(private readonly FinancialExtension $financialExtension)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Customer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular(t('Customer'))
            ->setEntityLabelInPlural(t('Customers'))
            ->overrideTemplate('crud/detail', 'admin/customer/detail.html.twig')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        // Basic customer information
        yield TextField::new('name')
            ->setLabel(t('Name'));

        yield EmailField::new('email')
            ->setLabel(t('Email'));

        yield TelephoneField::new('phone')
            ->setLabel(t('Phone'));

        yield TextField::new('type')
            ->setLabel(t('Type'));

        // Timestamps
        yield DateTimeField::new('createdAt')
            ->hideOnForm()
            ->setLabel(t('Created At'));

        yield DateTimeField::new('updatedAt')
            ->onlyOnDetail()
            ->setLabel(t('Last Updated At'));
    }
}
