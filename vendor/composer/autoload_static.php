<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit68e3f7cbeb3eaa461327682f8c14536d
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPArtisan\\ShopShape\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPArtisan\\ShopShape\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'PHPArtisan\\ShopShape\\Controllers\\License' => __DIR__ . '/../..' . '/src/Controllers/License.php',
        'PHPArtisan\\ShopShape\\Controllers\\Log' => __DIR__ . '/../..' . '/src/Controllers/Log.php',
        'PHPArtisan\\ShopShape\\Controllers\\ProductAddon\\Addon' => __DIR__ . '/../..' . '/src/Controllers/ProductAddon/Addon.php',
        'PHPArtisan\\ShopShape\\Controllers\\ProductAddon\\Admin' => __DIR__ . '/../..' . '/src/Controllers/ProductAddon/Admin.php',
        'PHPArtisan\\ShopShape\\Controllers\\ProductAddon\\Cart' => __DIR__ . '/../..' . '/src/Controllers/ProductAddon/Cart.php',
        'PHPArtisan\\ShopShape\\Controllers\\ProductAddon\\Checkout' => __DIR__ . '/../..' . '/src/Controllers/ProductAddon/Checkout.php',
        'PHPArtisan\\ShopShape\\Controllers\\ProductAddon\\SinglePage' => __DIR__ . '/../..' . '/src/Controllers/ProductAddon/SinglePage.php',
        'PHPArtisan\\ShopShape\\Controllers\\Requirement' => __DIR__ . '/../..' . '/src/Controllers/Requirement.php',
        'PHPArtisan\\ShopShape\\Plugin' => __DIR__ . '/../..' . '/src/Plugin.php',
        'PHPArtisan\\ShopShape\\Traits\\Api' => __DIR__ . '/../..' . '/src/Traits/Api.php',
        'PHPArtisan\\ShopShape\\Traits\\FileSystem' => __DIR__ . '/../..' . '/src/Traits/FileSystem.php',
        'PHPArtisan\\ShopShape\\Traits\\Singleton' => __DIR__ . '/../..' . '/src/Traits/Singleton.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit68e3f7cbeb3eaa461327682f8c14536d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit68e3f7cbeb3eaa461327682f8c14536d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit68e3f7cbeb3eaa461327682f8c14536d::$classMap;

        }, null, ClassLoader::class);
    }
}
