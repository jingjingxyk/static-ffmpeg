# 构建依赖库容器镜像

> 目的： 提前构建好依赖库，使用时直接跳过依赖库构建步骤

> 借助容器的多阶段构建功能，提前构建好依赖库
> 工作目录位于 `var` 目录

## 构建依赖库容器镜像的2种方式说明

> 通过 docker commit 生成 比如 `phpswoole/swoole-cli-builder:1.6`
> 通过 Dockerfile 多阶段构建生成 比如 `docker.io/jingjingxyk/build-swoole-cli:all-dependencies-alpine`
> 二者容器镜像是一样的

## 准备 swoole-cli源码和依赖库源码

```bash
    sh sapi/multi-stage-build-dependencies/build-all-dependencies-init.sh
```

## 执行构建依赖库容器

```bash
  sh sapi/multi-stage-build-dependencies/build-all-dependencies-container.sh
```

## 使用提前构建好的依赖库

```bash

sh sapi/multistage-build-dependencies-container/all-dependencies-container-run.sh


# 新开终端进入容器
docker exec -it swoole-cli-all-dependencies-container sh 


composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
composer update --no-dev --optimize-autoloader

# 生成构建脚本
php prepare.php --with-build-type=release  +ds +inotify +apcu --with-download-mirror=https://swoole-cli.jingjingxyk.com/

# 这里可以直接跳过步骤 sh make.sh all-library 

# 执行 PHP 构建预处理
sh make.sh config 
# 执行 PHP 构建
sh make.sh build 

```

## 容器多阶段构建镜像参考文档

- [multistage-build](https://docs.docker.com/develop/develop-images/multistage-build/)
- [dockerfile mount type 挂载目录](https://docs.docker.com/engine/reference/builder/#run---mount)
- [dockerfiles-now-support-multiple-build-contexts](https://www.docker.com/blog/dockerfiles-now-support-multiple-build-contexts/)