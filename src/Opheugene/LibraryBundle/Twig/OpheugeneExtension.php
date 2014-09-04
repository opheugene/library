<?php
namespace Opheugene\LibraryBundle\Twig;

use Gregwar\Image\Image;

class OpheugeneExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('showImage', array($this, 'showImage')),
        );
    }

    public function showImage ($path, $width = 160, $height = null)
    {
    	if (strlen ($path) && is_readable ($_SERVER["DOCUMENT_ROOT"] . $path)) {
	    	
	    	// generating preview
	    	$src = "/".Image::open($_SERVER["DOCUMENT_ROOT"] . $path)
			    ->cropResize($width, $height)
			    ->jpeg();
	    	
    	} else $src = "/bundles/Opheugenelibrary/images/no-photo.jpg";

        return $src;
    }

    public function getName()
    {
        return 'opheugene_extension';
    }
}

?>