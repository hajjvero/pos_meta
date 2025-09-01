<?php

namespace App\Controller\Admin\Order;

use App\Entity\Order\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use function Symfony\Component\Translation\t;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular(t('Order'))
            ->setEntityLabelInPlural(t('Orders'))
            ->setDefaultSort(['date' => 'DESC'])
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')
            ->onlyOnDetail()
            ->setLabel(t('ID'));

        yield TextField::new('code')
            ->setLabel(t('Order Code'));

        yield AssociationField::new('customer')
            ->setLabel(t('Customer'));

        yield AssociationField::new('cashier')
            ->setLabel(t('Cashier'));

        yield NumberField::new('totalAmount')
            ->setLabel(t('Total Amount'))
            ->setNumDecimals(2)
            ->formatValue(function ($value) {
                return number_format($value, 2, '.', ',') . ' €';
            });

        yield DateTimeField::new('date')
            ->setLabel(t('Order Date'))
            ->setFormat('dd/MM/yyyy HH:mm');

        yield AssociationField::new('orderItems')
            ->setLabel(t('Order Items'))
            ->onlyOnDetail()
            ->formatValue(function ($value, Order $entity) {
                return $entity->getOrderItems()->count() . ' items';
            });

        yield AssociationField::new('payments')
            ->setLabel(t('Payments'))
            ->onlyOnDetail()
            ->formatValue(function ($value, Order $entity) {
                return $entity->getPayments()->count() . ' payments';
            });

        yield DateTimeField::new('createdAt')
            ->hideOnForm()
            ->setLabel(t('Created At'))
            ->setFormat('dd/MM/yyyy HH:mm');

        yield DateTimeField::new('updatedAt')
            ->onlyOnDetail()
            ->setLabel(t('Last Updated At'))
            ->setFormat('dd/MM/yyyy HH:mm');
    }
}