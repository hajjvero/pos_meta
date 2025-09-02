<?php

namespace App\Controller\Admin\Customer;

use App\Entity\Customer\Customer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use function Symfony\Component\Translation\t;

class CustomerCrudController extends AbstractCrudController
{
    public function __construct()
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
            ->overrideTemplate('crud/detail', 'admin/customer/detail.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        // Basic customer information
        yield TextField::new('name', t('Name'));

        yield EmailField::new('email', t('Email'));

        yield TelephoneField::new('phone', t('Phone'));

        yield ChoiceField::new('type', t('Type'))
            ->renderAsBadges([
                'regular' => 'success',
                'company' => 'primary',
                'association' => 'info',
                'government' => 'warning',
                'other' => 'secondary'
            ])->setTranslatableChoices([
                'regular' => t('Regular'),
                'company' => t('Company'),
                'association' => t('Association'),
                'government' => t('Government'),
                'other' => t('Other')
            ]);

        // Timestamps
        yield DateTimeField::new('createdAt', t('Created At'))
            ->hideOnForm();

        yield DateTimeField::new('updatedAt', t('Last Updated At'))
            ->onlyOnDetail();
    }
}
