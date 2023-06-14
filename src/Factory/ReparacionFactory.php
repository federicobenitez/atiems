<?php

namespace App\Factory;

use App\Entity\Reparacion;
use App\Repository\ReparacionRepository;
use SebastianBergmann\Type\FalseType;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Reparacion>
 *
 * @method        Reparacion|Proxy create(array|callable $attributes = [])
 * @method static Reparacion|Proxy createOne(array $attributes = [])
 * @method static Reparacion|Proxy find(object|array|mixed $criteria)
 * @method static Reparacion|Proxy findOrCreate(array $attributes)
 * @method static Reparacion|Proxy first(string $sortedField = 'id')
 * @method static Reparacion|Proxy last(string $sortedField = 'id')
 * @method static Reparacion|Proxy random(array $attributes = [])
 * @method static Reparacion|Proxy randomOrCreate(array $attributes = [])
 * @method static ReparacionRepository|RepositoryProxy repository()
 * @method static Reparacion[]|Proxy[] all()
 * @method static Reparacion[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Reparacion[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Reparacion[]|Proxy[] findBy(array $attributes)
 * @method static Reparacion[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Reparacion[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ReparacionFactory extends ModelFactory
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
            'agente' => self::faker()->text(100),
            'asignadoA' => self::faker()->text(100),
            'carga' => self::faker()->text(100),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'departamentoArea' => self::faker()->text(100),
            'equipo' => self::faker()->text(80),
            'estado' => 'sin revisar',
            'fechaInicio' => self::faker()->dateTime(),
            'notificado' => 'no'
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Reparacion $reparacion): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Reparacion::class;
    }
}
