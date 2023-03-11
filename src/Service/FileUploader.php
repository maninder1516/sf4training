<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader {   

    protected $logger;

    private $targetDirectory;

    public function __construct($logger, $targetDirectory) {
        $this->logger = $logger;
        $this->targetDirectory = $targetDirectory;
    }

    /**
     *  Upload file to the directory
     */
    public function upload(UploadedFile $file) {
        try {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
            $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();            
            // Move the file to the directory where file are stored
            try {
                $file->move($this->getTargetDirectory(), $fileName);
            } catch (FileException $e) {
                $this->logger->critical('Error occured while uploading the file!', [
                    'ERROR:' => $e
                ]);
            }

            return $fileName;
        } catch (\Exception $ex) {
            $this->logger->critical('Error occured while uploading the file!', [
                'ERROR:' => $ex
            ]);
        }
    }

    /**
     * Get the Target directory
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}