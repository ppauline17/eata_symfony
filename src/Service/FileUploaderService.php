<?php 
namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;

class FileUploaderService
{

    private $imagesDirectory;
    private $croppedImagesDirectory;
    private $documentsDirectory;

    public function __construct($imagesDirectory, $croppedImagesDirectory, $documentsDirectory)
    {
        $this->imagesDirectory = $imagesDirectory;
        $this->croppedImagesDirectory = $croppedImagesDirectory;
        $this->documentsDirectory = $documentsDirectory;
    }

    public function getImagesDirectory()
    {
        return $this->imagesDirectory;
    }

    public function getCroppedImagesDirectory()
    {
        return $this->croppedImagesDirectory;
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

    public function uploadCroppedImage($croppedImageData, $filename){
        $directory = $this->getCroppedImagesDirectory();
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        // Save the cropped image data to the target directory
        file_put_contents($directory.'/'.$filename, $croppedImageData);
    }

    public function uploadDocument(File $file, $label)
    {
        $filename = filter_var($label, FILTER_SANITIZE_EMAIL);
        $filename .= '_'.time();
        $filename .= '.'.$file->guessExtension();
        $directory = $this->getDocumentsDirectory();
        $file->move($directory, $filename);

        return $filename;
    }
}