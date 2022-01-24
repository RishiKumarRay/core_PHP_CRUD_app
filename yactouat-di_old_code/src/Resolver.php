<?php

namespace Yactouat\DI;

use Error;
use ReflectionClass;
use ReflectionException;
use Yactouat\Exceptions\DI\ResolverClassResolutionException;

/**
 * @description the goal of the resolver is to be able to instanciate known dependencies on the fly;
 *              it is able to do that with or without instanciation parameters;
 *              it can resolve type hinted dependencies if they have default params;
 *              it can also resolve singletons
 */
class Resolver {

    public function resolve(string $dependency, array $dynamicParams = []): mixed {

        $hasDynamicParams = count($dynamicParams) > 0;

        // checking the container first
        $container = Container::getInstance();
        if ($container->has($dependency))
            return $container->get($dependency);

        // checking functions and variables
        try {
            if (function_exists($dependency) ) 
                return $hasDynamicParams ? $dependency(...$dynamicParams) : $dependency();
            elseif (isset($GLOBALS[$dependency])) {
                if(is_callable($GLOBALS[$dependency])) {
                    return $hasDynamicParams ? $GLOBALS[$dependency](...$dynamicParams) : 
                        $GLOBALS[$dependency](); 
                }
                return $GLOBALS[$dependency];
            }
        } catch (\Throwable $th) {
            throw new Error("Dependency cannot be resolved due to wrong passed params");
        }

        // checking classes
        try {
            $ref = new ReflectionClass($dependency);
        } catch(ReflectionException $re) {
            throw ResolverClassResolutionException::fromClassName($dependency);
        }
        $refConstructor = $ref->getConstructor();
        $refConstructorParams = is_null($refConstructor) ? [] : $refConstructor->getParameters();
        if ($hasDynamicParams) {
            try {
                return $ref->newInstanceArgs($dynamicParams);
            } catch (\Throwable $th) {
                throw new Error("Dependency cannot be resolved due to wrong passed params");
            }
        }
        elseif (count($refConstructorParams) > 0) {
            $actualConstructorParams = [];
            foreach ($refConstructorParams as $param) {
                try {
                    array_push($actualConstructorParams, $param->getDefaultValue());
                } catch (ReflectionException $re) {
                    /**
                     * recursion happens in this catch block,
                     * as if a param has no default value, 
                     * then it's a dynamic dependency that needs to be resolved
                     */ 
                    array_push($actualConstructorParams, $this->resolve($param->getType()->getName()));
                }
            }
            return $ref->newInstanceArgs($actualConstructorParams);
        }
        else {
			if ($ref->isInstantiable()) { 
                return $ref->newInstance();
			}
            // singletons resolution
            return $dependency::getInstance();
        }

    } // EO resolve( method

}