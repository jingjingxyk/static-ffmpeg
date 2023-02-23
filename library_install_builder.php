<?php


function libraries_install_builder($p){


    install_libiconv($p);//没有 libiconv.pc 文件 不能使用 pkg-config 命令
    install_openssl($p);
    install_openssl_v3($p);
    install_openssl_v3_quic($p);
    install_libxml2($p); //依赖 libiconv
    install_libxslt($p); //依赖 libxml2 libiconv

    install_gmp($p); // 精度算术库

    install_bzip2($p);//没有 libbz2.pc 文件，不能使用 pkg-config 命令  BZIP2_LIBS=-L/usr/bizp2/lib -lbz2
    install_zlib($p);
    install_libjpeg($p);
    install_libgif($p);//没有 libgif.pc 文件，不能使用 pkg-config 命令
    install_libpng($p); //依赖 zlib


    install_brotli($p); //有多种安装方式，选择使用cmake 安装
    install_cares($p);  // swoole 使用 SWOOLE_CFLAGS 实现

    install_ninja($p); //需要自己构建，alpine 默认没有提供源
    install_harfbuzz($p); //依赖ninja （alpine ninja 需要源码编译)

    install_libwebp($p); //依赖 libgif libpng libjpeg
    install_freetype($p); //依赖 zlib bzip2 libpng  brotli(暂不启用)  HarfBuzz (暂不启用)
    install_sqlite3($p);
    install_icu($p); //依赖  -lstdc++
    install_oniguruma($p);

    install_liblz4($p); //有多种安装方式，选择cmake方式安装
    install_liblzma($p);
    install_libzstd($p); //zstd 依赖 lz4
    install_zip($p); //zip 依赖 openssl zlib bzip2  liblzma zstd 静态库 (liblzma库 zstd库 暂不启用）
    //install_bzip2_dev_latest($p);

    install_libedit($p);
    install_ncurses($p);
    install_readline($p);//依赖 ncurses
    install_imagemagick($p);//依赖 freetype png webp xml zip zlib

    install_libsodium($p);

    //install_coreutils($p);
    //install_gnulib($p);
    //install_libunistring($p); //coreutils 包含  libiconv
    //install_gettext($p);// gettext 包含 intl

    //解决依赖 apk add  gettext  coreutils
    install_libidn2($p);//依赖 intl libunistring ； (gettext库包含intl 、coreutils库包含libunistring );
    install_nghttp2($p);
    install_nghttp2($p);

    install_nettle($p); //加密库
    install_libtasn1($p);
    install_libexpat($p);
    install_unbound($p); //依赖 libsodium nghttp2 nettle openssl ibtasn1 libexpat
    install_p11_kit($p);
    # TLS/ESNI/ECH/DoT/DoH/  参考文档https://zhuanlan.zhihu.com/p/572101957
    # SSL 比较 https://curl.se/docs/ssl-compared.html
    install_gnutls($p); //依赖 gmp libiconv  libtasn1 libzip  libzstd libbrotli libzlib
    install_boringssl($p);//需要 golang
    install_wolfssl($p);//

    //参考 ：HTTP3 and QUIC 有多种实现   curl 使用 http3 参考： https://curl.se/docs/http3.html
    install_nghttp3($p); // 使用 GnuTLS或者wolfss，这样就不用更换openssl版本了 ；


    install_ngtcp2($p); //依赖gnutls nghttp3
    install_quiche($p); // 依赖 boringssl ，需要 rust ；
    install_msh3($p);  //需要安装库 bsd-compat-headers 解决 sys/queue.h 不存在的问题

    install_curl($p); //curl 依赖 openssl c-ares brotli libzstd idn(暂不启用) libidn2 libnghttp2 libnghttp3


    install_libyaml($p);
    install_mimalloc($p);

    //参考 https://github.com/docker-library/php/issues/221
    install_pgsql($p);//依赖 openssl libxml2 libxslt  zlib readline icu libxml2 libxslt
    install_libffi($p);

    install_libfastcommon($p);
    install_libserverframe($p);
    install_fastdfs($p); //依赖 libfastcommon libserverframe


    install_php_internal_extensions($p); //安装内置扩展; ffi  pgsql pdo_pgsql

    install_php_extension_micro($p);
    //install_php_extension_fastdfs($p);
    //install_php_extension_libevent($p);
    //install_php_extension_zstd($p);
    //install_php_extension_libuv($p);

    if ($p->getOsType() == 'macos') {
        install_bison($p);  // 源码编译bison
        install_php_internal_extension_curl_patch($p);  //修改 `ext/curl/config.m4` ，去掉 `HAVE_CURL` 检测
    }

    if ($p->getOsType() == 'win') {
        install_re2c($p);
    }

# 扩展 mbstring 依赖 oniguruma 库
# 扩展 intl 依赖 ICU 库
# 扩展 gd 依赖 libpng，freetype 库 ；  freetype 依赖 zlib bzip2 libpng  brotli 等;  libwebp 依赖 giflib
# 扩展 mongodb 依赖 openssl, zlib, ICU 等库
# 本项目 opcache 是必装扩展，否则编译报错，不想启用opcache，需要修改源码: main/main.c
# 本项目 swoole  是必装扩展，否则 sh make.sh archive 无法打包

# php7 不支持openssl V3 ，PHP8 支持openssl V3 , openssl V3 默认库目录 /usr/openssl/lib64

# label: build_env_bin , php_extension_patch , php_internal_extension , php_extension ,extension_library

# pdo_pgsql,pdo_oci,pdo_odbc,ldap,ffi

    /**
    # 需要特别设置的地方

    export  CPPFLAGS=$(pkg-config  --cflags --static  libpq libcares libffi icu-uc icu-io icu-i18n readline )
    LIBS=$(pkg-config  --libs --static   libpq libcares libffi icu-uc icu-io icu-i18n readline )
    export LIBS="$LIBS -L/usr/lib -lstdc++"

     */
    if(0) {
        install_libevent($p);
        install_libuv($p);
        install_libev($p);

        install_libunwind($p); //使用 libunwind 可以很方便的获取函数栈中的内容，极大的方便了对函数间调用关系的了解。
        install_socat($p);
        install_jemalloc($p);
        install_tcmalloc($p);

        install_aria2($p);
        install_bazel($p);
        install_libelf($p);
        install_libbpf($p);
        install_valgrind($p);
        install_snappy($p);
        install_kerberos($p);
        install_fontconfig($p);
        install_pcre2($p);
        install_pgsql_test($p);
    }

}