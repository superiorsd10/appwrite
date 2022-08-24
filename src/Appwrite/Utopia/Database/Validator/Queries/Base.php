<?php

namespace Appwrite\Utopia\Database\Validator\Queries;

use Appwrite\Utopia\Database\Validator\IndexedQueries;
use Appwrite\Utopia\Database\Validator\Query\Limit;
use Appwrite\Utopia\Database\Validator\Query\Offset;
use Appwrite\Utopia\Database\Validator\Query\Cursor;
use Appwrite\Utopia\Database\Validator\Query\Filter;
use Appwrite\Utopia\Database\Validator\Query\Order;
use Utopia\Config\Config;
use Utopia\Database\Database;
use Utopia\Database\Document;

class Base extends IndexedQueries
{
    /**
     * Expression constructor
     *
     * @param string $collection
     * @param string[] $allowedAttributes
     */
    public function __construct(string $collection, array $allowedAttributes)
    {
        $collection = Config::getParam('collections', [])[$collection];
        // array for constant lookup time
        $allowedAttributesLookup = [];
        foreach ($allowedAttributes as $attribute) {
            $allowedAttributesLookup[$attribute] = true;
        }

        $attributes = [];
        foreach ($collection['attributes'] as $attribute) {
            $key = $attribute['$id'];
            if (!isset($allowedAttributesLookup[$key])) {
                continue;
            }

            $attributes[] = new Document([
                'key' => $key,
                'type' => $attribute['type'],
                'array' => $attribute['array'],
            ]);
        }

        $attributes[] = new Document([
            'key' => '$id',
            'type' => Database::VAR_STRING,
            'array' => false,
        ]);
        $attributes[] = new Document([
            'key' => '$createdAt',
            'type' => Database::VAR_DATETIME,
            'array' => false,
        ]);
        $attributes[] = new Document([
            'key' => '$updatedAt',
            'type' => Database::VAR_DATETIME,
            'array' => false,
        ]);

        $indexes = [];
        foreach ($allowedAttributes as $attribute) {
            $indexes[] = new Document([
                'status' => 'available',
                'type' => Database::INDEX_KEY,
                'attributes' => [$attribute]
            ]);
        }

        $validators = [
            new Limit(),
            new Offset(),
            new Cursor(),
            new Filter($attributes),
            new Order($attributes),
        ];

        parent::__construct($attributes, $indexes, ...$validators);
    }
}