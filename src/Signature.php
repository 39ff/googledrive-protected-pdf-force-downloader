<?php

namespace koulab\googledrive;

use Psr\Http\Message\ResponseInterface;

class Signature{

    protected $driveSignature;
    protected $imageToken;

    public function __construct($driveSignature = null , $imageToken = null){
        if(isset($driveSignature)){
            $this->driveSignature = $driveSignature;
        }
        if(isset($imageToken)){
            $this->imageToken = $imageToken;
        }
    }

    /**
     * @return mixed
     */
    public function getDriveSignature()
    {
        return $this->driveSignature;
    }

    /**
     * @return mixed
     */
    public function getImageToken()
    {
        return $this->imageToken;
    }

    /**
     * @param mixed $driveSignature
     */
    public function setDriveSignature($driveSignature): void
    {
        $this->driveSignature = $driveSignature;
    }

    /**
     * @param mixed $imageToken
     */
    public function setImageToken($imageToken): void
    {
        $this->imageToken = $imageToken;
    }

}