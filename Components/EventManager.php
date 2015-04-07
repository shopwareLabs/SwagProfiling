<?php

class EventManager extends Enlight_Event_EventManager {

    /**
     * @return Shopware_Plugins_Frontend_Profiling_Bootstrap
     */
    protected function getPluginBootstrap() {
        return Shopware()->Plugins()->Frontend()->SwagProfiling();
    }

    /**
     * @param $events Enlight_Event_EventManager
     */
    public function __construct(Enlight_Event_EventManager $events) {
        $this->listeners = $events->getAllListeners();
    }

    public function notify($event, $eventArgs = null) {
        $this->getPluginBootstrap()->addEvent(
            $event,
            'notify',
            $this->getListeners($event),
            $eventArgs
        );

        return parent::notify($event, $eventArgs);
    }

    public function filter($event, $value, $eventArgs = null) {
        if (isset($eventArgs) && is_array($eventArgs)) {
            $eventArgs = new Enlight_Event_EventArgs($eventArgs);
        } elseif (!isset($eventArgs)) {
            $eventArgs = new Enlight_Event_EventArgs();
        } elseif (!$eventArgs instanceof Enlight_Event_EventArgs) {
            throw new Enlight_Event_Exception('Parameter "eventArgs" must be an instance of "Enlight_Event_EventArgs"');
        }
        $eventArgs->setReturn($value);

        $this->getPluginBootstrap()->addEvent(
            $event,
            'filter',
            $this->getListeners($event),
            $eventArgs
        );

        return parent::filter($event, $value, $eventArgs);
    }

    public function notifyUntil($event, $eventArgs = null)
    {
        $this->getPluginBootstrap()->addEvent(
            $event,
            'notifyUntil',
            $this->getListeners($event),
            $eventArgs
        );
        return parent::notifyUntil($event, $eventArgs);
    }

    public function getListeners($event) {
        $additionalEventListeners = $this->getPluginBootstrap()->getAdditionalListeners($event);

        foreach($additionalEventListeners as $additionalEventListener) {
            $this->registerListener($additionalEventListener);
        }
        return parent::getListeners($event);
    }
}