<?php
namespace App\Library\PHPThumb;

interface PluginInterface
{
    /**
     * @param  PHPThumb $phpthumb
     * @return PHPThumb
     */
    public function execute($phpthumb);
}
