<?php

class Pathfindtestmodule
{
    public function pathDisp()
    {
        $base = Tools::getHttpHost(true);
        return $base . __PS_BASE_URI__ . 'modules/';
    }
}
