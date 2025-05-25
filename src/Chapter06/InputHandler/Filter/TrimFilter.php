<?php

namespace Cookbook\Chapter06\InputHandler\Filter;

class TrimFilter implements FilterInterface
{
    public function __construct(private FilterInterface $next)
    {
    }

    public function apply(object $entity): void
    {
        $rules = method_exists($entity, 'getFilterRules') ? $entity->getFilterRules() : [];
        foreach ($rules as $field => $meta) {
            if (!($meta['trim'] ?? false)) {
                continue;
            }

            $getter = $meta['getter'] ?? null;
            $setter = $meta['setter'] ?? null;

            if ($getter && $setter && method_exists($entity, $getter) && method_exists($entity, $setter)) {
                $value = $entity->{$getter}();
                $entity->{$setter}(trim($value));
            }
        }

        $this->next->apply($entity);
    }
}
