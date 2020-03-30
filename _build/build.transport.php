<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
$tstart = explode(' ', microtime());
$tstart = $tstart[1] + $tstart[0];

if (!defined('MOREPROVIDER_BUILD')) {
    /* define version */
    define('PKG_NAME', 'SimpleCart Authorize.net');
    define('PKG_NAMESPACE', 'simplecart_authorizenet');
    define('PKG_NAME_LOWER', PKG_NAMESPACE);
    define('PKG_VERSION', '2.0.2');
    define('PKG_RELEASE', 'pl');

    /* load modx */
    require_once dirname(dirname(__FILE__)) . '/config.core.php';
    require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
    $modx= new modX();
    $modx->initialize('mgr');
    $modx->setLogLevel(modX::LOG_LEVEL_INFO);
    $modx->setLogTarget('ECHO');


    echo '<pre>';
    flush();
    $targetDirectory = dirname(dirname(__FILE__)) . '/_packages/';
}
else {
    $targetDirectory = MOREPROVIDER_BUILD_TARGET;
}

/* define build paths */
$root = dirname(dirname(__FILE__)).'/';

$sources = array(
    'root' => $root,
    'build' => $root . '_build/',
    'data' => $root . '_build/data/',
    'validators' => $root . '_build/validators/',
    'resolvers' => $root . '_build/resolvers/',
    'install_options' => $root . '_build/setup/',
    'lexicon' => $root . 'core/components/' . PKG_NAME_LOWER . '/lexicon/',
    'docs' => $root . 'core/components/' . PKG_NAME_LOWER . '/docs/',
    'source_assets' => $root . 'assets/components/' . PKG_NAME_LOWER . '/',
    'source_core' => $root . 'core/components/' . PKG_NAME_LOWER . '/',
    'chunks' => $root . 'core/components/' . PKG_NAMESPACE . '/elements/chunks/'
);
unset($root);


$modx->loadClass('transport.xPDOTransport', XPDO_CORE_PATH, true, true);
$modx->loadClass('transport.modPackageBuilder', '', false, true);

/** @var modPackageBuilder $builder * */
$builder = new modPackageBuilder($modx);
$builder->directory = $targetDirectory;
$builder->createPackage(PKG_NAMESPACE, PKG_VERSION, PKG_RELEASE);

$builder->registerNamespace(PKG_NAMESPACE,false,true,'{core_path}components/'.PKG_NAMESPACE.'/', '{assets_path}components/'.PKG_NAMESPACE.'/');

$modx->log(xPDO::LOG_LEVEL_INFO, 'Transport package for ' . PKG_NAME. ' created.'); flush();

/* @var modCategory $category - add category for our component */
$category = $modx->newObject('modCategory');
$category->set('id', 1);
$category->set('category', PKG_NAME);

/* add chunks */
$modx->log(modX::LOG_LEVEL_INFO, 'Packaging in chunks...');
$chunks = include $sources['data'].'transport.chunks.php';
if (empty($chunks)) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'Could not package in chunks.');
}
if (is_array($chunks)) {
    $category->addMany($chunks);
}

/* create category vehicle */
$attr = array(
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
    xPDOTransport::RELATED_OBJECT_ATTRIBUTES => array (
        'Chunks' => array(
            xPDOTransport::PRESERVE_KEYS => false,
            xPDOTransport::UPDATE_OBJECT => true,
            xPDOTransport::UNIQUE_KEY => 'name',
        ),
    ),
);
$vehicle = $builder->createVehicle($category,$attr);
$modx->log(modX::LOG_LEVEL_INFO,'Adding in Validators...');
// Add the validator to check server requirements
$vehicle->validate('php', array('source' => $sources['validators'] . 'requirements.script.php'));
$vehicle->validate('php', array('source' => $sources['validators'] . 'preinstall.script.php'));

// Add file resolvers
$modx->log(modX::LOG_LEVEL_INFO, 'Adding core file resolvers to category...');

$vehicle->resolve('file',array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . 'components/';",
));
$vehicle->resolve('php', array('source' => $sources['resolvers'].'resolve.records.php'));
$vehicle->resolve('php', array('source' => $sources['resolvers'].'resolve.setup-options.php'));
$vehicle->resolve('php', array('source' => $sources['resolvers'].'guzzle3_cacert.resolver.php'));

$builder->putVehicle($vehicle);
unset($vehicle);

$modx->log(xPDO::LOG_LEVEL_INFO, 'Files for "' . PKG_NAME_LOWER . '" packaged.'); flush();

$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['source_core'] . '/docs/license.txt'),
    'readme' => file_get_contents($sources['source_core'] . '/docs/readme.txt'),
    'changelog' => file_get_contents($sources['source_core'] . '/docs/changelog.txt'),
    'setup-options' => array(
        'source' => $sources['install_options'] . 'input.options.php',
    ),
    'requires' => array(
        'simplecart' => '>=2.5.0',
    )
));

$modx->log(modX::LOG_LEVEL_INFO,'Packing up transport package zip...'); flush();
$builder->pack();


$tend = explode(" ", microtime());
$tend = $tend[1] + $tend[0];
$totalTime = sprintf("%2.4f s", ($tend - $tstart));

$modx->log(modX::LOG_LEVEL_INFO, "Package Built. Execution time: {$totalTime}");
$modx->log(modX::LOG_LEVEL_INFO, "\n-----------------------------\n".PKG_NAME . ' ' . PKG_VERSION.'-'.PKG_RELEASE." built\n-----------------------------");
