<?php

namespace koulab\googledrive;


use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use GDrivePDFSniffRequestorException;

class GDrivePDFSniffRequestor{

    protected $headers = [
        'Referer'=>'https://drive.google.com/',
        'User-Agent'=>'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.117 Safari/537.36'
    ];

    /**
     * @return mixed
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param mixed $headers
     */
    public function setHeaders($headers): void
    {
        $this->headers = $headers;
    }

    public function getDownloadCredentials($url,$headers = []) : RequestInterface{
        $c =  new Request('GET',$url,array_merge($this->headers,$headers));

        return $c;
    }

    public function getImageKey(Signature $signature) : RequestInterface{
        $q = [
            'ds'=>$signature->getDriveSignature(),
            'ck'=>'drive',
            'p'=>'proj',
            'sp'=>''
        ];
        $c = new Request('GET','https://drive.google.com/viewerng/upload?'.http_build_query($q));

        return $c;
    }

    public function getMeta(Signature $signature) : RequestInterface{
        if(is_null($signature->getImageToken())){
            throw new GDrivePDFSniffRequestorException("Signature imageToken required.");
        }
        $q = [
            'id'=>$signature->getImageToken()
        ];
        $c = new Request('GET','https://drive.google.com/viewerng/meta?'.http_build_query($q));

        return $c;
    }

    public function download(Signature $signature,Meta $meta, $page = 1) : RequestInterface{
        if($meta->getPages() < $page){
            throw new GDrivePDFSniffRequestorException("Maximum Page < Input Pages is wrong params.");
        }
        $q = [
            'id'=>$signature->getImageToken(),
            'page'=>$page,
            'skiphighlight'=>'true',
            'w'=>$meta->getMaxPageWidth(),
            'webp'=>'true'
        ];
        $c = new Request('GET','https://drive.google.com/viewerng/img?'.http_build_query($q));
        return $c;
    }
}