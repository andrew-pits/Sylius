<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Sylius\Bundle\AddressingBundle\Factory\ZoneFactoryInterface;
use Sylius\Bundle\SettingsBundle\Manager\SettingsManagerInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Mateusz Zalewski <mateusz.zalewski@lakion.com>
 */
class ZoneContext implements Context
{
    /**
     * @var array
     */
    private $euMembers = [
        'BE', 'BG', 'CZ', 'DK', 'DE', 'EE', 'IE', 'GR', 'ES',
        'FR', 'IT', 'CY', 'LV', 'LT', 'LU', 'HU', 'MT', 'NL',
        'AT', 'PL', 'PT', 'RO', 'SI', 'SK', 'FI', 'SE', 'GB',
    ];

    /**
     * @var RepositoryInterface
     */
    private $zoneRepository;

    /**
     * @var SettingsManagerInterface
     */
    private $settingsManager;

    /**
     * @var ZoneFactoryInterface
     */
    private $zoneFactory;

    /**
     * @param RepositoryInterface $zoneRepository
     * @param SettingsManagerInterface $settingsManager
     * @param ZoneFactoryInterface $zoneFactory
     */
    public function __construct(RepositoryInterface $zoneRepository, SettingsManagerInterface $settingsManager, ZoneFactoryInterface $zoneFactory)
    {
        $this->zoneRepository = $zoneRepository;
        $this->settingsManager = $settingsManager;
        $this->zoneFactory = $zoneFactory;
    }

    /**
     * @Transform :zone zone
     * @Transform zone :zone
     */
    public function getZoneByCode($zone)
    {
        $existingZone = $this->zoneRepository->findOneBy(['code' => $zone]);
        if (null === $existingZone) {
            throw new \InvalidArgumentException(sprintf('Zone with code "%s" does not exist', $zone));
        }

        return $existingZone;
    }

    /**
     * @Given /^there is "EU" zone containing all members of European Union$/
     */
    public function thereIsEUZoneContainingAllMembersOfEuropeanUnion()
    {
        $zone = $this->zoneFactory->createWithMembers($this->euMembers);
        $zone->setType(ZoneInterface::TYPE_COUNTRY);
        $zone->setCode('EU');
        $zone->setName('European Union');

        $this->zoneRepository->add($zone);
    }

    /**
     * @Given default tax zone is :taxZone
     */
    public function defaultTaxZoneIs($taxZone)
    {
        $zone = $this->getZoneByCode($taxZone);

        $settings = $this->settingsManager->loadSettings('sylius_taxation');
        $settings->set('default_tax_zone', $zone);
        $this->settingsManager->saveSettings('sylius_taxation', $settings);
    }
}
