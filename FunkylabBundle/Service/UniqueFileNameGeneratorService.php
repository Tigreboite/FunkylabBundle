<?php

namespace Tigreboite\FunkylabBundle\Service;

use Cocur\Slugify\Slugify;

class UniqueFileNameGeneratorService 
{
    private $slugify;
    private $original_filename;

    function __construct(Slugify $slugify) {
        $this->slugify = $slugify;
    }

    public function generate($filename)
    {
        $this->original_filename = $filename;
        $fileInfos = $this->getFileInfos($filename);
        $full_path = $this->buildFullPath($fileInfos['dirname'], $this->slugify->slugify($fileInfos['filename_without_ext']), $fileInfos['extension']);

        $final_name = $this->autoGenerate($full_path, 0);

        return basename($final_name);
    }

    private function autoGenerate($filename, $increment)
    {
        if (file_exists($filename)) {
            $increment++;
            $fileInfos = $this->getFileInfos($filename);

            $fileInfosOrigin = $this->getFileInfos($this->original_filename);
            $filename = $this->buildFullPath($fileInfos['dirname'], $fileInfosOrigin['filename_without_ext'].'_'.$increment, $fileInfos['extension']);
            
            return $this->autoGenerate($filename, $increment);
        }
        else return $filename;
    }

    private function getFileInfos($filename)
    {
        $dirname = dirname($filename);
        $filename = basename($filename);

        $tmp = explode('.', $filename);
        $ext = $tmp[count($tmp)-1];

        $filename_without_ext = "";

        for ($i=0; $i < count($tmp)-1; $i++) { 
            $filename_without_ext .= $tmp[$i];
        }

        return [
            'extension' => $ext,
            'filename_without_ext' => $filename_without_ext,
            'dirname' => $dirname
        ];
    }

    private function buildFullPath($dirname, $filename_without_ext, $ext)
    {
        return $dirname.'/'.$filename_without_ext.'.'.$ext;
    }
}
