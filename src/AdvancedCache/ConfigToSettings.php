<?php

namespace A3020\AdvancedCache;

use A3020\AdvancedCache\Rules\RuleInterface;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;

final class ConfigToSettings implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * Converts an array from the config to a Settings object.
     *
     * @param string $handle
     * @param array $config
     * @return CacheSettings
     */
    public function convert($handle, $config)
    {
        /** @var CacheSettings $settings */
        $settings = $this->app->make(CacheSettings::class);
        $settings->setHandle($handle);

        if (isset($config['differs_per_page'])) {
            $settings->setDiffersPerPage($config['differs_per_page']);
        }

        if (isset($config['differs_per_user'])) {
            $settings->setDiffersPerUser($config['differs_per_user']);
        }

        if (isset($config['expires_in'])) {
            $settings->setExpiresIn($config['expires_in']);
        }

        if (isset($config['flush_rules'])) {
            foreach ($config['flush_rules'] as $rule) {
                if (!isset($rule['fqn']) || !class_exists($rule['fqn'])) {
                    continue;
                }

                /** @var RuleInterface $flushRule */
                $flushRule = $this->app->make($rule['fqn']);
                $flushRule->fromConfigArray($rule);

                $settings->addFlushRule($flushRule);
            }
        }

        return $settings;
    }
}
