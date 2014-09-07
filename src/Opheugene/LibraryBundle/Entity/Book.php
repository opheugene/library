<?php

namespace Opheugene\LibraryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Book
 *
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="Opheugene\LibraryBundle\Entity\BookRepository")
 */
class Book
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="cover", type="text")
     */
    private $cover;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $coverPath;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="text")
     */
    private $file;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $filePath;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="read", type="date")
     */
    private $read;

    /**
     * @var boolean
     *
     * @ORM\Column(name="download", type="boolean")
     */
    private $download;

    // ******************************************
    //          Getters and Setters
    // ******************************************

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return Book
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set author
     *
     * @param  string $author
     * @return Book
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set read
     *
     * @param  \DateTime $read
     * @return Book
     */
    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }

    /**
     * Get read
     *
     * @return \DateTime
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * Set download
     *
     * @param  boolean $download
     * @return Book
     */
    public function setDownload($download)
    {
        $this->download = $download;

        return $this;
    }

    /**
     * Get download
     *
     * @return boolean
     */
    public function getDownload()
    {
        return $this->download;
    }

    // ******************************************
    //                  Files
    // ******************************************

    // cover ====================================

    /**
     * Set cover
     *
     * @param  string $cover
     * @return Book
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return string
     */
    public function getCover()
    {
        return strlen($this->cover) && is_readable($this->getUploadRootDir().'/cover/'.$this->cover)
            ? $this->getUploadDir().'/cover/'.$this->cover : '';
    }

    // form input for cover ---------------------

    /**
     * Sets coverPath.
     *
     * @param UploadedFile $file
     */
    public function setCoverPath(UploadedFile $file = null)
    {
        $this->coverPath = $file;
    }

    /**
     * Get coverPath.
     *
     * @return UploadedFile
     */
    public function getCoverPath()
    {
        return $this->coverPath;
    }

    public function uploadCover()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getCoverPath()) {
            return;
        }

        // delete old cover file
        $this->deleteCover();

        $subdir = rand(0, 9);
        $filename = uniqid().".".$this->getCoverPath()->guessExtension();
        $this->getCoverPath()->move($this->getUploadRootDir().'/cover/'.$subdir, $filename);

        // set the path property to the filename where you've saved the file
        $this->cover = $subdir."/".$filename;

        // clean up the file property as you won't need it anymore
        $this->coverPath = null;
    }

    // file ===================================

    /**
     * Set file
     *
     * @param  string $file
     * @return Book
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return strlen($this->file)  && is_readable($this->getUploadRootDir().'/book/'.$this->file)
        ? $this->getUploadDir().'/book/'.$this->file : '';
    }

    // form input for file ---------------------

    /**
     * Sets filePath.
     *
     * @param UploadedFile $file
     */
    public function setFilePath(UploadedFile $file = null)
    {
        $this->filePath = $file;
    }

    /**
     * Get filePath.
     *
     * @return UploadedFile
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    public function uploadFile()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFilePath()) {
            return;
        }

        // delete old book file
        $this->deleteFile();

        $subdir = rand(0, 9);
        $filename = uniqid().".".$this->getFilePath()->guessExtension();
        $this->getFilePath()->move($this->getUploadRootDir().'/book/'.$subdir, $filename);

        // set the path property to the filename where you've saved the file
        $this->file = $subdir."/".$filename;

        // clean up the file property as you won't need it anymore
        $this->filePath = null;
    }

    // global upload methods -------------------

    protected function getRootDir()
    {
        return __DIR__."/../../../../web";
    }

    public function getUploadRootDir()
    {
        return $this->getRootDir().$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return '/upload';
    }

    // delete
    public function deleteCover()
    {
        $tmp = $this->getCover();
        if ($this->getCover() && is_readable($this->getUploadRootDir().'/cover/'.$this->cover)) {
            unlink($this->getRootDir().$tmp);
            $this->checkEmptyDir($tmp);
        }
        $this->setCover(false);
    }

    public function deleteFile()
    {
        $tmp = $this->getFile();
        if ($this->getFile() && is_readable($this->getUploadRootDir().'/book/'.$this->file)) {
            unlink($this->getRootDir().$tmp);
            $this->checkEmptyDir($tmp);
        }
        $this->setFile(false);
    }

    public function deleteUploadedFiles()
    {
        $tmpCover = $this->getCover();
        if ($this->getCover() && is_readable($this->getUploadRootDir().'/cover/'.$this->cover)) {
            unlink($this->getRootDir().$tmpCover);
            $this->checkEmptyDir($tmpCover);
        }
        $tmpFile = $this->getFile();
        if ($this->getFile() && is_readable($this->getUploadRootDir().'/book/'.$this->file)) {
            unlink($this->getRootDir().$tmpFile);
            $this->checkEmptyDir($tmpFile);
        }
    }

    // check is dir empty
    public function checkEmptyDir($filePath)
    {
        if ($filePath) {

            // get dir
            $arPathInfo = pathinfo($this->getRootDir().$filePath);
            $dir = $arPathInfo["dirname"];

            // check files
            if (!is_readable($dir)) {
                return;
            }
            $handle = opendir($dir);
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    return;
                }
            }

            // delete dir
            rmdir($dir);
        }
    }
}
