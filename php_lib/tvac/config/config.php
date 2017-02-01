<?php
/* * --------------------------------------------------
 * 本番公開用コンフィグ
 * -------------------------------------------------- */
return array(

    /**
     * DBの設定です。
     *
     * それぞれ、PDOに渡すための
     * dsn,
     * username,
     * password,
     * driver_options
     * を記述します。
     */
    'DBParams' => array(
        'mysql:host=localhost;dbname=tvac_db',
        'tvac',
        'fd3d6agh379',
        array(
            PDO::ATTR_PERSISTENT               => false,
            PDO::ATTR_ERRMODE                  => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES         => false,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            PDO::ATTR_DEFAULT_FETCH_MODE       => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND       => "SET CHARACTER SET `UTF8`"
        ),

    ),

    /**
     * DBの暗号化カラムに使う暗号キーです。
     */
    'CryptKey' => 'Wq2PcTehiM87VZtY',

    /**
     * PEARのMail_smtpに渡す内容です。
     */
    'SMTPParams' => array(
        'host' => 'localhost',
        'port' => 25,
    ),


);
