<?php

namespace App\Factory;

use App\Entity\Servicio;
use App\Repository\ServicioRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Servicio>
 *
 * @method        Servicio|Proxy create(array|callable $attributes = [])
 * @method static Servicio|Proxy createOne(array $attributes = [])
 * @method static Servicio|Proxy find(object|array|mixed $criteria)
 * @method static Servicio|Proxy findOrCreate(array $attributes)
 * @method static Servicio|Proxy first(string $sortedField = 'id')
 * @method static Servicio|Proxy last(string $sortedField = 'id')
 * @method static Servicio|Proxy random(array $attributes = [])
 * @method static Servicio|Proxy randomOrCreate(array $attributes = [])
 * @method static ServicioRepository|RepositoryProxy repository()
 * @method static Servicio[]|Proxy[] all()
 * @method static Servicio[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Servicio[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Servicio[]|Proxy[] findBy(array $attributes)
 * @method static Servicio[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Servicio[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ServicioFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'agente' => self::faker()->text(255),
            'asignadoA' => self::faker()->text(255),
            'carga' => self::faker()->text(255),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'departamentoArea' => self::faker()->text(255),
            'equipo' => self::faker()->text(255),
            'estado' => self::faker()->text(255),
            'fechaInicio' => self::faker()->dateTime(),
            'notificado' => self::faker()->text(255),
            'tipo' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Servicio $servicio): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Servicio::class;
    }
}
