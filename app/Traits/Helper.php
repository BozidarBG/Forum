<?php
/**
 * Created by PhpStorm.
 * User: Bole
 * Date: 28.9.2018
 * Time: 16:54
 */

namespace App\Traits;


trait Helper
{
    public function shortenText($field, $word_count){

        $text=$field;
        if(str_word_count($field, 0)> $word_count){

            $words=str_word_count($field, 2);
            $pos=array_keys($words);
            $text=substr($field, 0, $pos[$word_count]).'...';
        }
        return $text;
    }

    public function showDate($time=null){
        return $time ? $this->updated_at->format('d.m.Y @ H:i:s') :$this->updated_at->format('d.m.Y') ;
    }

    public function showCreated($time=null){
        return $time ? $this->created_at->format('d.m.Y @ H:i:s') :$this->created_at->format('d.m.Y') ;
    }
}