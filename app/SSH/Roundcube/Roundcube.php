<?php

namespace App\SSH\Roundcube;

use App\Models\Site;
use App\SSH\HasScripts;

class Roundcube
{
    use HasScripts;

    public function install(Site $site): void
    {
        $site->server->ssh()->exec(
            $this->getScript('install.sh', [
                'version' => $site->type_data['version'],
                'path' => $site->path,
                'domain' => $site->domain,
                'support_url' => $site->type_data['support_url'],
            ]),
            'install-roundcube'
        );
    }
}
