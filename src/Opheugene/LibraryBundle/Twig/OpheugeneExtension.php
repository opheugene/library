<?php
namespace Opheugene\LibraryBundle\Twig;

use Gregwar\Image\Image;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class OpheugeneExtension extends \Twig_Extension
{
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('showImage', array($this, 'showImage')),
        );
    }

    public function showImage($path, $width = 160, $height = null)
    {
        $documentRoot = $this->container->getParameter("document_root");
        if (strlen($path) && is_readable($documentRoot . $path)) {

            // generating preview
            $src = "/".Image::open($documentRoot . $path)
                ->cropResize($width, $height)
                ->jpeg();

        } else {
            $src = "/bundles/Opheugenelibrary/images/no-photo.jpg";
        }

        return $src;
    }

    public function getName()
    {
        return 'opheugene_extension';
    }
}
