<?php

namespace App\Factory;

use App\Entity\Teammate;
use App\Repository\TeammateRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Teammate>
 *
 * @method        Teammate|Proxy create(array|callable $attributes = [])
 * @method static Teammate|Proxy createOne(array $attributes = [])
 * @method static Teammate|Proxy find(object|array|mixed $criteria)
 * @method static Teammate|Proxy findOrCreate(array $attributes)
 * @method static Teammate|Proxy first(string $sortedField = 'id')
 * @method static Teammate|Proxy last(string $sortedField = 'id')
 * @method static Teammate|Proxy random(array $attributes = [])
 * @method static Teammate|Proxy randomOrCreate(array $attributes = [])
 * @method static TeammateRepository|RepositoryProxy repository()
 * @method static Teammate[]|Proxy[] all()
 * @method static Teammate[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Teammate[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Teammate[]|Proxy[] findBy(array $attributes)
 * @method static Teammate[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Teammate[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class TeammateFactory extends ModelFactory
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
            'category' => self::faker()->text(50),
            'hierarchy' => self::faker()->numberBetween(1, 3),
            'job' => self::faker()->text(100),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Teammate $teammate): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Teammate::class;
    }
}
