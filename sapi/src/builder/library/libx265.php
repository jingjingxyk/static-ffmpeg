<?php

use SwooleCli\Library;
use SwooleCli\Preprocessor;

return function (Preprocessor $p) {
    $libx265_prefix = LIBX265_PREFIX;
    $numa_prefix = NUMA_PREFIX;
    $lib = new Library('libx265');
    $lib->withHomePage('https://www.videolan.org/developers/x265.html')
        ->withLicense('https://bitbucket.org/multicoreware/x265_git/src/master/COPYING', Library::LICENSE_LGPL)
        ->withUrl('http://ftp.videolan.org/pub/videolan/x265/x265_2.7.tar.gz')
        ->withFile('libx265_2.7.tar.gz')
        ->withManual('https://bitbucket.org/multicoreware/x265_git.git')
        ->withPrefix($libx265_prefix)
        ->withCleanBuildDirectory()
        ->withCleanPreInstallDirectory($libx265_prefix)
        ->withConfigure(
            <<<EOF
            mkdir -p build
            cd build
            # apk add nasm
            cmake \
            -G"Unix Makefiles" ../source  \
            -DCMAKE_INSTALL_PREFIX={$libx265_prefix} \
            -DCMAKE_BUILD_TYPE=Release  \
            -DBUILD_SHARED_LIBS=OFF  \
            -DBUILD_STATIC_LIBS=ON \
            -DENABLE_LIBNUMA=ON \
            -DNuma_ROOT={$numa_prefix}

EOF
        )
        ->withPkgName('x265')
        ->withBinPath($libx265_prefix . '/bin/')
        ->withDependentLibraries('numa');

    $p->addLibrary($lib);
};