<?php

namespace SwooleCli;

abstract class Project
{
    public string $name;
    public string $url;
    public string $path = '';
    public string $file = '';
    public string $md5sum = '';

    public string $manual = '';
    public string $homePage = '';

    public string $license = '';

    public string $prefix = '';

    public array $deps = [];

    public int $licenseType = self::LICENSE_SPEC;

    public const LICENSE_SPEC = 0;
    public const LICENSE_APACHE2 = 1;
    public const LICENSE_BSD = 2;
    public const LICENSE_GPL = 3;
    public const LICENSE_LGPL = 4;
    public const LICENSE_MIT = 5;
    public const LICENSE_PHP = 6;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function withLicense(string $license, int $licenseType = self::LICENSE_SPEC): static
    {
        $this->license = $license;
        $this->licenseType = $licenseType;
        return $this;
    }

    public function withHomePage(string $homePage): static
    {
        $this->homePage = $homePage;
        return $this;
    }

    public function withManual(string $manual): static
    {
        $this->manual = $manual;
        return $this;
    }

    public function depends(string ...$libs): static
    {
        $this->deps += $libs;
        return $this;
    }

    public function withMd5sum(string $md5sum): static
    {
        $this->md5sum = $md5sum;
        return $this;
    }

    public function withUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    public function withFile(string $file): static
    {
        $this->file = $file;
        return $this;
    }

    public function withDownloadScript(string $downloadDirName, string $script): static
    {
        $this->enableDownloadScript = true;
        $this->downloadScript = $script;
        $this->downloadDirName = $downloadDirName;
        return $this;
    }

    public string $gnupg = '';

    public string $wiki = '';

    public string $docs = '';

    public string $documentation = '';

    public string $downloadScript = '';

    public string $downloadDirName = '';

    public bool $enableDownloadScript = false;

    public bool $enableWithDownloadMirrorURL = true;

    public function withDocumentation(string $documentation): static
    {
        $this->documentation = $documentation;
        return $this;
    }

    public function withGnuPG(string $gpg): static
    {
        $this->gnupg = $gpg;
        return $this;
    }

    public function disableWithDownloadMirrorURL(): static
    {
        $this->enableWithDownloadMirrorURL = false;
        return $this;
    }
}