<?php

namespace Phower\Image\Adapter;

use Phower\ImageTest\Adapter\GdAdapterTest;
use Phower\ImageTest\Adapter\ImagickAdapterTest;

function function_exists($name)
{
    if ($name === 'gd_info' && null !== GdAdapterTest::$mockFunctionExistsGdInfo) {
        return GdAdapterTest::$mockFunctionExistsGdInfo;
    }
    return \function_exists($name);
}

function gd_info()
{
    if (null !== GdAdapterTest::$mockGdInfoResult) {
        return GdAdapterTest::$mockGdInfoResult;
    }
    return \gd_info();
}

function extension_loaded($name)
{
    if ($name === 'imagick' && null !== ImagickAdapterTest::$mockExtensionLoadedImagick) {
        return ImagickAdapterTest::$mockExtensionLoadedImagick;
    }

    return \extension_loaded($name);
}

function version_compare($version1, $version2, $operator = null)
{
    if (null !== ImagickAdapterTest::$mockVersionCompare) {
        return ImagickAdapterTest::$mockVersionCompare;
    }
    return \version_compare($version1, $version2, $operator);
}

function file_put_contents($filename, $data, $flags = 0, $context = null)
{
    if (null !== ImagickAdapterTest::$mockFilePutContents) {
        return ImagickAdapterTest::$mockFilePutContents;
    }
    return \file_put_contents($filename, $data, $flags, $context);
}
