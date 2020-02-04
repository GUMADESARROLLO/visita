<?php

/**
 * Created by PhpStorm.
 * User: maryan.espinoza
 * Date: 16/10/2019
 * Time: 16:15
 */
class git_version
{
    const MAJOR = 1;
    const MINOR = 3;

    public static  function get()
    {
        $commitHash = trim(exec('git rev-list --all --count'));
        return sprintf('v%s.%s.%s', self::MAJOR, self::MINOR,  $commitHash);
    }
}
