<?php

use SwooleCli\Library;
use SwooleCli\Preprocessor;

return function (Preprocessor $p) {
    $boost_prefix = BOOST_PREFIX;
    $icu_prefix = ICU_PREFIX;
    $lib = new Library('boost');
    $lib->withHomePage('https://www.boost.org/')
        ->withLicense('https://www.boost.org/users/license.html', Library::LICENSE_SPEC)
        ->withUrl('https://boostorg.jfrog.io/artifactory/main/release/1.81.0/source/boost_1_81_0.tar.gz')
        ->withManual('https://www.boost.org/doc/libs/1_81_0/more/getting_started/index.html')
        ->withManual('https://github.com/boostorg/wiki/wiki/')
        ->withManual('https://github.com/boostorg/wiki/wiki/Getting-Started%3A-Overview')
        ->withManual('https://www.boost.org/build/')
        ->withManual('https://www.boost.org/build/doc/html/index.html')
        ->withManual('https://www.boost.org/doc/libs/1_83_0/more/getting_started/windows.html')
        ->withManual('https://www.boost.org/doc/libs/1_83_0/more/getting_started/unix-variants.html')
        ->withPrefix($boost_prefix)
        ->withCleanBuildDirectory()
        ->withCleanPreInstallDirectory($boost_prefix)
        ->withBuildLibraryCached(false)
        ->withBuildScript(
            <<<EOF

            # export Boost_USE_STATIC_LIBS=on
            # ./bootstrap.sh --help

            #./bootstrap.sh \
            #--prefix={$boost_prefix} \
            #--with-icu={$icu_prefix} \
            #--with-toolset={$p->get_C_COMPILER()} \
            #--with-libraries=all \
            #--show-libraries

            # ./b2 --help
            # ./b2 release headers link=static runtime-link=static

            mkdri -p build-dir
            cd tools/build/
            sh   bootstrap.sh
            ./b2  \
            --build-dir={$p->getBuildDir()}/boost/build-dir \
            toolset={$p->get_C_COMPILER()} \
             --build-type=complete  \
             release headers link=static runtime-link=static


            ./b2 --release install --prefix={$boost_prefix}


EOF
        )
        ->withPkgName('boost')
        ->withDependentLibraries('zlib','bzip2','liblzma','libzstd','icu','libiconv')

    ;

    $p->addLibrary($lib);
};
