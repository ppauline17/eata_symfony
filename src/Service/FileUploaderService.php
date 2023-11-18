<?php 
namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;

class FileUploaderService
{

    private $imagesDirectory;
    private $documentsDirectory;

    public function __construct($imagesDirectory, $documentsDirectory)
    {
        $this->imagesDirectory = $imagesDirectory;
        $this->documentsDirectory = $documentsDirectory;
    }

    public function getImagesDirectory()
    {
        return $this->imagesDirectory;
    }

    public function getDocumentsDirectory()
    {
        return $this->documentsDirectory;
    }

    public function uploadPicture(File $file)
    {
        $filename = bin2hex(random_bytes(6)).'.'.$file->guessExtension();
        $directory = $this->getImagesDirectory();
        $file->move($directory, $filename);

        return $filename;
    }

    public function uploadDocument(File $file)
    {
        $filename = bin2hex(random_bytes(6)).'.'.$file->guessExtension();
        $directory = $file->guessExtension() == "pdf" ? $this->getDocumentsDirectory() : $directory = $this->getImagesDirectory();
        $file->move($directory, $filename);

        return $filename;
    }
}