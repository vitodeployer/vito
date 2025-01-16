<?php

namespace App\Models;

use App\Actions\Service\Manage;
use App\Enums\ServiceStatus;
use App\Exceptions\ServiceInstallationFailed;
use App\SSH\Services\Database\Database as DatabaseAlias;
use App\SSH\Services\PHP\PHP as PHPAlias;
use App\SSH\Services\ProcessManager\ProcessManager;
use App\SSH\Services\ServiceInterface;
use App\SSH\Services\Webserver\Webserver as WebServerAlias;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @template TService of ServiceInterface = ServiceInterface
 */
class Service extends AbstractModel
{
    use HasFactory;

    protected $fillable = [
        'server_id',
        'type',
        'type_data',
        'name',
        'version',
        'unit',
        'logs',
        'status',
        'is_default',
    ];

    protected $casts = [
        'server_id' => 'integer',
        'type_data' => 'json',
        'is_default' => 'boolean',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function (Service $service) {
            if (array_key_exists($service->name, config('core.service_units'))) {
                $service->unit = config('core.service_units')[$service->name][$service->server->os][$service->version];
            }
        });
    }

    public static array $statusColors = [
        ServiceStatus::READY => 'success',
        ServiceStatus::INSTALLING => 'warning',
        ServiceStatus::INSTALLATION_FAILED => 'danger',
        ServiceStatus::UNINSTALLING => 'warning',
        ServiceStatus::FAILED => 'danger',
        ServiceStatus::STARTING => 'warning',
        ServiceStatus::STOPPING => 'warning',
        ServiceStatus::RESTARTING => 'warning',
        ServiceStatus::STOPPED => 'danger',
        ServiceStatus::ENABLING => 'warning',
        ServiceStatus::DISABLING => 'warning',
        ServiceStatus::DISABLED => 'gray',
    ];

    /**
     * @return BelongsTo<Server, $this>
     */
    public function server(): BelongsTo
    {
        return $this->belongsTo(Server::class);
    }

    /**
     * @return ProcessManager|DatabaseAlias|PHPAlias|WebServerAlias
     * @phpstan-return TService
     */
    public function handler(): ServiceInterface
    {
        $handler = config('core.service_handlers')[$this->name];

        return new $handler($this);
    }

    /**
     * @throws ServiceInstallationFailed
     */
    public function validateInstall($result): void
    {
        if (! Str::contains($result, 'Active: active')) {
            throw new ServiceInstallationFailed;
        }
    }

    public function start(): void
    {
        $this->unit && app(Manage::class)->start($this);
    }

    public function stop(): void
    {
        $this->unit && app(Manage::class)->stop($this);
    }

    public function restart(): void
    {
        $this->unit && app(Manage::class)->restart($this);
    }

    public function enable(): void
    {
        $this->unit && app(Manage::class)->enable($this);
    }

    public function disable(): void
    {
        $this->unit && app(Manage::class)->disable($this);
    }
}
