<?php

namespace A3020\AdvancedCache;

final class CacheSettings
{
    /**
     * @var CacheHandleGenerator
     */
    private $handleGenerator;

    /** @var string */
    private $handle;

    /** @var int */
    private $seconds;

    /** @var bool */
    private $differsPerPage = false;

    /** @var bool */
    private $differsPerUser = false;

    /** @var \A3020\AdvancedCache\Rules\RuleInterface[] */
    private $flushRules = [];

    /** @var \Closure|null */
    private $onlyCacheIfClosure;

    public function __construct(CacheHandleGenerator $handleGenerator)
    {
        $this->setDontExpire();
        $this->handleGenerator = $handleGenerator;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @return string
     */
    public function getCacheHandle()
    {
        return $this->handleGenerator->generate($this);
    }

    /**
     * Set the base identifier of the cached item.
     *
     * Try to keep the name short to prevent
     * endless folders in the cache directory.
     *
     * @param string $handle
     *
     * @return CacheSettings
     */
    public function setHandle($handle)
    {
        // Make sure the handle stays short.
        $handle = substr($handle, 0, 20);

        $this->handle = $handle;

        return $this;
    }

    /**
     * @return int
     */
    public function getExpiresIn()
    {
        return (int) $this->seconds;
    }

    /**
     * @param int $seconds
     *
     * @return $this
     */
    public function setExpiresIn($seconds)
    {
        $this->seconds = (int) $seconds;

        return $this;
    }

    /**
     * @return $this
     */
    public function setDontExpire()
    {
        $this->seconds = 9999999999;

        return $this;
    }

    /**
     * @return bool
     */
    public function getDiffersPerPage()
    {
        return (bool) $this->differsPerPage;
    }

    /**
     * Set whether the cache differs per page.
     *
     * If the output (e.g. the block) uses the current page id
     * set this setting to true. Keep it false in if the output
     * is the same across pages. E.g. a footer element.
     *
     * Keep in mind that if you have lots of pages, this could
     * result in many cache entries! (use wisely)
     *
     * @param bool $differsPerPage
     *
     * @return CacheSettings
     */
    public function setDiffersPerPage($differsPerPage = true)
    {
        $this->differsPerPage = (bool) $differsPerPage;

        return $this;
    }

    /**
     * @return bool
     */
    public function getDiffersPerUser()
    {
        return (bool) $this->differsPerUser;
    }

    /**
     * Set whether the cache differs per user.
     *
     * Set to true if the output could differ among
     * users. Keep in mind that if you have lots of users
     * this might result in lots of cache entries!
     *
     * @param bool $differsPerUser
     *
     * @return CacheSettings
     */
    public function setDiffersPerUser($differsPerUser = true)
    {
        $this->differsPerUser = (bool) $differsPerUser;

        return $this;
    }

    /**
     * Add a flush rule to this cache setting.
     *
     * A flush rule defines at which point the cache should
     * be invalidated. They work together with concrete5 events.
     *
     * @param Rules\RuleInterface $rule
     *
     * @return CacheSettings
     *
     * @example <code>$settings->addFlushRule((new FlushOnPageActions())->setPageType('blog'))</code>
     */
    public function addFlushRule(Rules\RuleInterface $rule)
    {
        $this->flushRules[] = $rule;

        return $this;
    }

    /**
     * @return Rules\RuleInterface[]
     */
    public function getFlushRules()
    {
        return $this->flushRules;
    }

    /**
     * @param $closure
     *
     * @return $this
     */
    public function onlyCacheIf($closure)
    {
        if ($closure instanceof \Closure) {
            $this->onlyCacheIfClosure = $closure;
        }

        return $this;
    }

    /**
     * If the closure evaluates to false, the cache will be surpassed.
     *
     * @return bool
     */
    public function shouldCache()
    {
        $closure = $this->onlyCacheIfClosure;

        return $closure === null ? true : $closure();
    }

    /**
     * Convert the settings to an array, to save in a config file.
     *
     * @return array
     */
    public function toConfigArray()
    {
        $flushRules = [];
        foreach ($this->getFlushRules() as $flushRule) {
            $flushRules[] = $flushRule->toConfigArray();
        }

        return [
            'expires_in' => $this->getExpiresIn(),
            'differs_per_page' => $this->getDiffersPerPage(),
            'differs_per_user' => $this->getDiffersPerUser(),
            'flush_rules' => $flushRules,
        ];
    }
}
