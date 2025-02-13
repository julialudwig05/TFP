<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2eabdb8e565973f5cc3f9a3ef0cd693c
{
    public static $prefixLengthsPsr4 = array (
        'j' => 
        array (
            'julia\\tfp\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'julia\\tfp\\' => 
        array (
            0 => __DIR__ . '/../..' . '/public/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2eabdb8e565973f5cc3f9a3ef0cd693c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2eabdb8e565973f5cc3f9a3ef0cd693c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2eabdb8e565973f5cc3f9a3ef0cd693c::$classMap;

        }, null, ClassLoader::class);
    }
}
