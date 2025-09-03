<?php

namespace App\Controller\Admin\User;

use App\Entity\User\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use function Symfony\Component\Translation\t;

class UserCrudController extends AbstractCrudController
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular(t('User'))
            ->setEntityLabelInPlural(t('Users'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', t('Name'));

        yield TextField::new('username', t('Username'));

        yield EmailField::new('email', t('Email'));

        yield TelephoneField::new('phone', t('Phone'));

        yield ChoiceField::new('roles', t('Roles'))
            ->setTranslatableChoices([
                'ROLE_USER' => t('User'),
                'ROLE_ADMIN' => t('Admin'),
                'ROLE_SUPER_ADMIN' => t('Super Admin')
            ])
            ->allowMultipleChoices()
            ->renderAsBadges([
                'ROLE_USER' => 'success',
                'ROLE_ADMIN' => 'primary',
                'ROLE_SUPER_ADMIN' => 'danger'
            ]);

        yield TextField::new('plainPassword', t('Password'))
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => t('Password'),
                    'attr' => [
                        'placeholder' => t('Enter password...'),
                        'autocomplete' => 'new-password',
                        'class' => 'form-control'
                    ],
                    'row_attr' => ['class' => 'col-md-8 col-xxl-6'],
                    'toggle' => true,
                    'use_toggle_form_theme' => true,
                    'button_classes' => ['btn', 'btn-outline-secondary', 'btn-sm', 'mt-1']
                ],
                'second_options' => [
                    'label' => t('Confirm Password'),
                    'attr' => [
                        'placeholder' => t('Confirm password...'),
                        'autocomplete' => 'new-password',
                        'class' => 'form-control'
                    ],
                    'row_attr' => ['class' => 'col-md-8 col-xxl-6'],
                    'toggle' => true,
                    'use_toggle_form_theme' => true,
                    'button_classes' => ['btn', 'btn-outline-secondary', 'btn-sm', 'mt-1']
                ],
                'invalid_message' => t('The password fields must match'),
                'required' => $pageName === Crud::PAGE_NEW,
                'mapped' => false
            ])
            ->onlyOnForms()
            ;

        yield DateTimeField::new('lastLogin', t('Last Login'))
            ->hideOnForm();

        yield DateTimeField::new('createdAt', t('Created At'))
            ->hideOnForm();

        yield DateTimeField::new('updatedAt', t('Last Updated At'))
            ->onlyOnDetail();
    }

    public function createNewForm(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormInterface
    {
        return parent::createNewFormBuilder($entityDto, $formOptions, $context)
            ->addEventListener(FormEvents::SUBMIT, $this->hashPassword(...))
            ->getForm();
    }

    public function createEditForm(EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormInterface
    {
        return parent::createEditFormBuilder($entityDto, $formOptions, $context)
            ->addEventListener(FormEvents::SUBMIT, $this->hashPassword(...))
            ->getForm();
    }

    private function hashPassword($event): void
    {
        $form = $event->getForm();

        $plainPassword = $form->get('plainPassword')->getData();
        if (empty($plainPassword)) {
            return;
        }

        /** @var User $user */
        $user = $form->getData();
        $hashedPassword = $this->hasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
    }
}
