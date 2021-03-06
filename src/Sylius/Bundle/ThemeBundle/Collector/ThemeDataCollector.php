<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\ThemeBundle\Collector;

use Sylius\Bundle\ThemeBundle\Context\ThemeContextInterface;
use Sylius\Bundle\ThemeBundle\Model\ThemeInterface;
use Sylius\Bundle\ThemeBundle\Repository\ThemeRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollectorInterface;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
class ThemeDataCollector implements DataCollectorInterface, \Serializable
{
    /**
     * @var ThemeInterface
     */
    private $usedTheme;

    /**
     * @var ThemeInterface[]
     */
    private $themeHierarchy;

    /**
     * @var ThemeInterface[]
     */
    private $allThemes;

    /**
     * @var ThemeRepositoryInterface
     */
    private $themeRepository;

    /**
     * @var ThemeContextInterface
     */
    private $themeContext;

    /**
     * @param ThemeRepositoryInterface $themeRepository
     * @param ThemeContextInterface $themeContext
     */
    public function __construct(
        ThemeRepositoryInterface $themeRepository,
        ThemeContextInterface $themeContext
    ) {
        $this->themeRepository = $themeRepository;
        $this->themeContext = $themeContext;
    }

    /**
     * @return ThemeInterface
     */
    public function getUsedTheme()
    {
        return $this->usedTheme;
    }

    /**
     * @return ThemeInterface[]
     */
    public function getThemeHierarchy()
    {
        return $this->themeHierarchy;
    }

    /**
     * @return ThemeInterface[]
     */
    public function getAllThemes()
    {
        return $this->allThemes;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->usedTheme = $this->themeContext->getTheme();
        $this->themeHierarchy = $this->themeContext->getThemeHierarchy();
        $this->allThemes = $this->themeRepository->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize([$this->usedTheme, $this->themeHierarchy, $this->allThemes]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        list($this->usedTheme, $this->themeHierarchy, $this->allThemes) = unserialize($serialized);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_theme';
    }
}
