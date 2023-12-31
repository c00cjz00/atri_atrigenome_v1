<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9f0afa0b63f7379492d849fedef0b05e
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPLogin\\' => 9,
        ),
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPLogin\\' => 
        array (
            0 => __DIR__ . '/../..' . '/login/class',
        ),
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'EasyPeasyICS' => __DIR__ . '/..' . '/phpmailer/phpmailer/extras/EasyPeasyICS.php',
        'Firebase\\JWT\\BeforeValidException' => __DIR__ . '/..' . '/firebase/php-jwt/src/BeforeValidException.php',
        'Firebase\\JWT\\ExpiredException' => __DIR__ . '/..' . '/firebase/php-jwt/src/ExpiredException.php',
        'Firebase\\JWT\\JWK' => __DIR__ . '/..' . '/firebase/php-jwt/src/JWK.php',
        'Firebase\\JWT\\JWT' => __DIR__ . '/..' . '/firebase/php-jwt/src/JWT.php',
        'Firebase\\JWT\\Key' => __DIR__ . '/..' . '/firebase/php-jwt/src/Key.php',
        'Firebase\\JWT\\SignatureInvalidException' => __DIR__ . '/..' . '/firebase/php-jwt/src/SignatureInvalidException.php',
        'PHPLogin\\AdminFunctions' => __DIR__ . '/../..' . '/login/class/adminfunctions.php',
        'PHPLogin\\AppConfig' => __DIR__ . '/../..' . '/login/class/appconfig.php',
        'PHPLogin\\AuthorizationHandler' => __DIR__ . '/../..' . '/login/class/authorizationhandler.php',
        'PHPLogin\\CSRFHandler' => __DIR__ . '/../..' . '/login/class/csrfhandler.php',
        'PHPLogin\\CookieHandler' => __DIR__ . '/../..' . '/login/class/cookiehandler.php',
        'PHPLogin\\DbConn' => __DIR__ . '/../..' . '/login/class/dbconn.php',
        'PHPLogin\\ImgHandler' => __DIR__ . '/../..' . '/login/class/imghandler.php',
        'PHPLogin\\LoginHandler' => __DIR__ . '/../..' . '/login/class/loginhandler.php',
        'PHPLogin\\MailHandler' => __DIR__ . '/../..' . '/login/class/mailhandler.php',
        'PHPLogin\\MiscFunctions' => __DIR__ . '/../..' . '/login/class/miscfunctions.php',
        'PHPLogin\\PageConstructor' => __DIR__ . '/../..' . '/login/class/pageconstructor.php',
        'PHPLogin\\PasswordHandler' => __DIR__ . '/../..' . '/login/class/passwordhandler.php',
        'PHPLogin\\PermissionHandler' => __DIR__ . '/../..' . '/login/class/permissionhandler.php',
        'PHPLogin\\ProfileData' => __DIR__ . '/../..' . '/login/class/profiledata.php',
        'PHPLogin\\RoleHandler' => __DIR__ . '/../..' . '/login/class/rolehandler.php',
        'PHPLogin\\TokenHandler' => __DIR__ . '/../..' . '/login/class/tokenhandler.php',
        'PHPLogin\\Traits\\PermissionTrait' => __DIR__ . '/../..' . '/login/class/Traits/permissiontrait.php',
        'PHPLogin\\Traits\\RoleTrait' => __DIR__ . '/../..' . '/login/class/Traits/roletrait.php',
        'PHPLogin\\UserData' => __DIR__ . '/../..' . '/login/class/userdata.php',
        'PHPLogin\\UserHandler' => __DIR__ . '/../..' . '/login/class/userhandler.php',
        'PHPMailer' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.phpmailer.php',
        'PHPMailerOAuth' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.phpmaileroauth.php',
        'PHPMailerOAuthGoogle' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.phpmaileroauthgoogle.php',
        'POP3' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.pop3.php',
        'SMTP' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.smtp.php',
        'ntlm_sasl_client_class' => __DIR__ . '/..' . '/phpmailer/phpmailer/extras/ntlm_sasl_client.php',
        'phpmailerException' => __DIR__ . '/..' . '/phpmailer/phpmailer/class.phpmailer.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9f0afa0b63f7379492d849fedef0b05e::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9f0afa0b63f7379492d849fedef0b05e::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9f0afa0b63f7379492d849fedef0b05e::$classMap;

        }, null, ClassLoader::class);
    }
}
