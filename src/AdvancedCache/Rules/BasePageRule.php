<?php

namespace A3020\AdvancedCache\Rules;

abstract class BasePageRule implements RuleInterface
{
    private $pageTypes = [];
    private $pageTemplates = [];

    /**
     * @param \Concrete\Core\Page\Event $event
     *
     * @inheritdoc
     */
    public function invalidate($event)
    {
        if ($this->pageTypes
            && !in_array($event->getPageObject()->getPageTypeHandle(), $this->pageTypes)
        ) {
            return false;
        }

        if ($this->pageTemplates
            && !in_array($event->getPageObject()->getPageTemplateHandle(), $this->pageTemplates)
        ) {
            return false;
        }

        return true;
    }

    /**
     * @param string|string[] $pageType
     *
     * @return $this
     */
    public function onlyForPageTypes($pageType)
    {
        if (is_array($pageType)) {
            $this->pageTypes = $pageType;
        } else {
            $this->pageTypes[] = (string) $pageType;
        }

        return $this;
    }

    /**
     * @param string|string[] $pageTemplate
     *
     * @return $this
     */
    public function onlyForPageTemplates($pageTemplate)
    {
        if (is_array($pageTemplate)) {
            $this->pageTemplates = $pageTemplate;
        } else {
            $this->pageTemplates[] = (string) $pageTemplate;
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function toConfigArray()
    {
        return [
            'fqn' => get_called_class(),
            'page_types' => $this->pageTypes,
            'page_templates' => $this->pageTemplates,
        ];
    }

    /**
     * @inheritdoc
     */
    public function fromConfigArray($config)
    {
        if (isset($config['page_types'])) {
            $this->onlyForPageTypes($config['page_types']);
        }

        if (isset($config['page_templates'])) {
            $this->onlyForPageTemplates($config['page_templates']);
        }
    }
}
