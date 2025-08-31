<?php

namespace App\Controller\Admin\Setting;

use App\Entity\Option\Option;
use App\Repository\Option\OptionRepository;
use App\Service\Setting\SettingService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * SettingController manages the application settings interface in the admin panel.
 *
 * This controller handles displaying and updating application settings through a web interface.
 * Settings are stored as key-value pairs in the database using the Option entity.
 * The controller supports both GET requests to display the settings form and POST requests
 * to update the settings.
 *
 * Features:
 * - Displays settings in a tabbed interface with different categories
 * - Handles form submission to update settings
 * - Refreshes cache after settings updates
 * - Redirects back to settings page after successful update
 *
 * Settings Categories:
 * - General (date/time formats, timezone, language)
 * - Company information
 * - Financial settings
 * - Order management
 * - Receipt configuration
 * - Product settings
 *
 * @author Hamza hajjaji <hajjvero@gmail.com>
 */
#[Route('/admin/setting', name: 'admin_setting', methods: ['GET', 'POST'])]
final class SettingController extends AbstractController
{
    /**
     * Constructor for SettingController.
     *
     * @param SettingService $settingService Service for managing application settings and cache
     * @param OptionRepository $optionRepository Repository for accessing Option entities
     * @param EntityManagerInterface $entityManager Entity manager for database operations
     * @param AdminUrlGenerator $adminUrlGenerator URL generator for EasyAdmin routes
     */
    public function __construct(
        private readonly SettingService $settingService,
        private readonly OptionRepository $optionRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly AdminUrlGenerator $adminUrlGenerator
    )
    {}

    /**
     * Main controller action that handles both displaying the settings form (GET)
     * and processing form submissions (POST).
     *
     * GET Request:
     * Renders the settings page with a tabbed interface. The actual form fields
     * are defined in separate Twig templates included in the main template.
     *
     * POST Request:
     * Processes the submitted form data, updating all settings in the database.
     * After successful update:
     * 1. Persists all changes to the database
     * 2. Refreshes the settings cache
     * 3. Redirects back to the settings page
     *
     * @param Request $request The HTTP request object
     *
     * @return Response The HTTP response (rendered page or redirect)
     *
     * @throws InvalidArgumentException When there's an issue with cache operations
     */
    public function __invoke(Request $request): Response
    {
        // Check if the request method is POST
        if ($request->isMethod('POST')) {
            // Get all options
            $options = $this->optionRepository->findAll();

            // Loop through each submitted option
            foreach ($request->request->all() as $key => $value) {
                /**
                 * Find the option with the given key
                 * @var ?Option $option
                 */
                $option = array_find($options, fn($option) => $option->getOptionKey() === $key);

                // If the option exists, update its value
                if ($option) {
                    $option->setOptionValue($value);
                    $this->optionRepository->save($option);
                }
            }

            // Persist all changes
            $this->entityManager->flush();
            // Refresh the settings cache
            $this->settingService->refreshCache();

            // Redirect back to the settings page
            return $this->redirect($this->adminUrlGenerator->setRoute('admin_setting')->generateUrl());
        }

        // Render the settings page
        return $this->render('admin/setting/index.html.twig');
    }
}
