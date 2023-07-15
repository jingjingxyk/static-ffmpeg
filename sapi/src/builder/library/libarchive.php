<?php

use SwooleCli\Library;
use SwooleCli\Preprocessor;

return function (Preprocessor $p) {
    $libarchive_prefix = LIBARCHIVE_PREFIX;
    $libiconv_prefix = ICONV_PREFIX;
    $bzip2_prefix = BZIP2_PREFIX;
    $p->addLibrary(
        (new Library('libarchive'))
            ->withHomePage('https://github.com/libarchive/libarchive.git')
            ->withManual('https://www.libarchive.org/')
            ->withManual('https://github.com/libarchive/libarchive/wiki/BuildInstructions')
            ->withLicense('https://github.com/libarchive/libarchive/blob/master/COPYING', Library::LICENSE_SPEC)
            ->withUrl('https://github.com/libarchive/libarchive/releases/download/v3.6.2/libarchive-3.6.2.tar.gz')
            ->withPrefix($libarchive_prefix)
            ->withConfigure(
                <<<EOF
                # apk add groff  util-linux

                sh build/autogen.sh
                ./configure --help
                PACKAGES="\$PACKAGES openssl gmp libxml-2.0 liblz4 liblzma zlib libzstd nettle"
                CPPFLAGS="$(pkg-config  --cflags-only-I  --static \$PACKAGES) -I{$bzip2_prefix}/include " \
                LDFLAGS="$(pkg-config   --libs-only-L    --static \$PACKAGES) -L{$bzip2_prefix}/lib" \
                LIBS="$(pkg-config      --libs-only-l    --static \$PACKAGES) -lbz2" \
                ./configure \
                --prefix={$libarchive_prefix} \
                --enable-shared=no \
                --enable-static=yes \
                --enable-bsdcpio=static \
                --enable-bsdtar=static \
                --with-nettle \
                --with-openssl \
                --with-xml2 \
                --with-lz4 \
                --with-zstd \
                --with-lzma \
                --with-bz2lib \
                --with-zlib \
                --with-libiconv-prefix={$libiconv_prefix} \
                --without-mbedtls


                 # make distcheck


                # cmake .
EOF


            )
            ->withPkgName('libarchive')
            ->withBinPath($libarchive_prefix . '/bin/')
            ->withDependentLibraries(
                'openssl',
                'libxml2',
                'zlib',
                'liblzma',
                'liblz4',
                'libiconv',
                'libzstd',
                'bzip2',
                'nettle'
            )
    );
};