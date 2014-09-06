<?php

namespace Opheugene\LibraryBundle\Tests\Entity;

use Opheugene\LibraryBundle\Entity\Book;

class BookTest extends \PHPUnit_Framework_TestCase
{
    public function testDeleteCover()
    {
        // creating the book
        $book = new Book();

        // creating image
        $image = imagecreatetruecolor(10, 10);
        imagefill($image, 0, 0, 0xFFFFFF);

        // prepare path
        $path = $book->getUploadRootDir().'/cover/test';
        $name = "/image.jpg";
        if (!is_dir($path)) {
            mkdir($path, 0775, true);
        }

        imagejpeg($image, $path.$name);
        @imagedestroy($image);

        $book->setCover("test/image.jpg");
        $book->deleteCover();

        // check
        //$this->assertFalse((bool)$book->getCover());
        $this->assertFileNotExists($path.$name);
    }
}
