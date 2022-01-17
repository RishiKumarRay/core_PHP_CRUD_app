<?php

namespace App\IOC;

use ReflectionClass;
use ReflectionException;

final class Resolver
{

    /**
     * @throws ReflectionException
     */
    public function resolveClass(string $className, array $args) {
        try {
            $reflected = new ReflectionClass($className);
        } catch (ReflectionException $re) {
            // we return null if the class dependency cannot be resolved
            return null;
        }

        if ($reflected->hasMethod("getSingletonInstance")) {
            $getSingletonInstance = $reflected->getMethod("getSingletonInstance");
            // checking if we do respect the expected signature of our \App\Singleton getSingletonInstance method
            if($getSingletonInstance->isPublic() && $getSingletonInstance->isStatic()) {
                // here, the object we pass is null because the singleton method is supposed to return the desired object itself when called
                try {
                    return $getSingletonInstance->invokeArgs(null, $args);
                } catch (ReflectionException $e) {
                    return null;
                }
            }
        }

        // if not a singleton we proceed as a regular class
        try {
            return $reflected->newInstanceArgs($args);
        } catch (ReflectionException $e) {
            return null;
        }
    }

}