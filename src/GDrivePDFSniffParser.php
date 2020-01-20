<?php

namespace koulab\googledrive;

use Psr\Http\Message\ResponseInterface;
use GDrivePDFSniffParserException;

class GDrivePDFSniffParser{

    public static function parseMeta(ResponseInterface $response) : Meta{
        preg_match('/{(.*?)}/m',$response->getBody()->getContents(),$m);

        if(isset($m[0])){
            $json = json_decode($m[0]);
            return new Meta($json->pages,$json->maxPageWidth);
        }

        throw new GDrivePDFSniffParserException("Failed to get PDF Metadata");
    }

    public static function parseImageToken(ResponseInterface $response,Signature $signature = null) : Signature{
        if(!isset($signature)){
            $signature = new Signature();
        }
        preg_match('/"img":"img\?id=([0-9a-zA-Z_-]+)"}/m',$response->getBody()->getContents(),$m);
        $signature->setImageToken($m[1]);

        return $signature;
    }

    public static function parseItemJsonSignature(ResponseInterface $response,Signature $signature = null) : Signature{
        if(!isset($signature)){
            $signature = new Signature();
        }
        preg_match('/itemJson:(.*)\s};/ms',$response->getBody()->getContents(),$m);
        $items = (json_decode($m[1]));
        $ds = null;
        foreach($items as $item){
            if(!is_array($item) && strpos($item,'https://drive.google.com/viewerng/upload') !== false){
                $query = parse_url($item,PHP_URL_QUERY);
                parse_str($query,$params);
                $signature->setDriveSignature($params['ds']);
                break;
            }
        }

        return $signature;
    }


}