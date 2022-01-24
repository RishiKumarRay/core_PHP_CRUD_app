<?php

namespace Yactouat\DI;

use Yactouat\Exceptions\DI\DependencyNotFoundException;
use Yactouat\Exceptions\DI\EmptyEntryIdException;
use Yactouat\Exceptions\DI\InvalidMassAddingEntriesException;
use Yactouat\Interfaces\SingletonInterface;
use Yactouat\Arrays\ArraysTrait as Arrays;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface, SingletonInterface {

    use Arrays;

    private array $_entries = [];
    private static $_instance;
    private Resolver $_resolver;

    private function __construct(){
        $this->_resolver = new Resolver();
    }

    public static function getInstance(): self {
        if (is_null(self::$_instance))
            self::$_instance = new Container();
        return self::$_instance;
    }

    public function clear(): void {
        $this->_entries = [];
        self::$_instance = null;
    }

    public function addEntries(array $entries): void {
        if ($this->_arrayIsList($entries))
            throw InvalidMassAddingEntriesException::fromSelf();
        foreach ($entries as $key => $value) {
            $this->addEntry($key, $value);
        }
    }

    public function addEntry(string $id, $value = null): void {
        if ( strlen($id) <= 0)
            throw EmptyEntryIdException::fromSelf();
        // if no value is provided for this entry, then its value is it's id
        if ($value == null) 
            $value = $id;
        $this->_entries[$id] = $value;
    }

    public function get(string $id) {
        /**
         * in this if block,
         * we try automatic resolution of the dependency if not set in entries;
         * if the dependency is resolved, we add its in the container,
         * if not we throw an exception on resolution failure
         */
        if (!isset($this->_entries[$id]))
            try {
                $this->_entries[$id] = $this->_resolver->resolve($id);
            } catch (\Throwable $th) {
                throw DependencyNotFoundException::fromEntryId($id);
            }
        return $this->_entries[$id];
    }

    public function has(string $id): bool {
        return isset($this->_entries[$id]);
    }

}