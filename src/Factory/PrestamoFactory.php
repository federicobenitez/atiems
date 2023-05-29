<?php

namespace App\Factory;

use App\Entity\Prestamo;
use App\Repository\PrestamoRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Prestamo>
 *
 * @method        Prestamo|Proxy create(array|callable $attributes = [])
 * @method static Prestamo|Proxy createOne(array $attributes = [])
 * @method static Prestamo|Proxy find(object|array|mixed $criteria)
 * @method static Prestamo|Proxy findOrCreate(array $attributes)
 * @method static Prestamo|Proxy first(string $sortedField = 'id')
 * @method static Prestamo|Proxy last(string $sortedField = 'id')
 * @method static Prestamo|Proxy random(array $attributes = [])
 * @method static Prestamo|Proxy randomOrCreate(array $attributes = [])
 * @method static PrestamoRepository|RepositoryProxy repository()
 * @method static Prestamo[]|Proxy[] all()
 * @method static Prestamo[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Prestamo[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Prestamo[]|Proxy[] findBy(array $attributes)
 * @method static Prestamo[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Prestamo[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class PrestamoFactory extends ModelFactory
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
            'carga' => self::faker()->text(255),
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'departamentoArea' => self::faker()->text(255),
            'estado' => self::faker()->text(255),
            'marca' => self::faker()->text(255),
            'modelo' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Prestamo $prestamo): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Prestamo::class;
    }
}
