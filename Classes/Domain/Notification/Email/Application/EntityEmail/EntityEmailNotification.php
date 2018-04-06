<?php

/*
 * Copyright (C) 2018
 * Nathan Boiron <nathan.boiron@gmail.com>
 * Romain Canon <romain.hydrocanon@gmail.com>
 *
 * This file is part of the TYPO3 NotiZ project.
 * It is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License, either
 * version 3 of the License, or any later version.
 *
 * For the full copyright and license information, see:
 * http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace CuyZ\Notiz\Domain\Notification\Email\Application\EntityEmail;

use CuyZ\Notiz\Core\Event\Event;
use CuyZ\Notiz\Core\Notification\CustomSettingsNotification;
use CuyZ\Notiz\Core\Property\PropertyEntry;
use CuyZ\Notiz\Domain\Notification\Email\Application\EntityEmail\Processor\EntityEmailNotificationProcessor;
use CuyZ\Notiz\Domain\Notification\Email\Application\EntityEmail\Settings\EntityEmailSettings;
use CuyZ\Notiz\Domain\Notification\Email\EmailNotification;
use CuyZ\Notiz\Domain\Notification\EntityNotification;
use CuyZ\Notiz\Domain\Property\Email;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\FlexFormService;

class EntityEmailNotification extends EntityNotification implements EmailNotification, CustomSettingsNotification
{
    /**
     * @var string
     */
    protected $layout;

    /**
     * @var string
     */
    protected $sender;

    /**
     * @var bool
     */
    protected $senderCustom;

    /**
     * @var string
     */
    protected $sendTo;

    /**
     * @var string
     */
    protected $sendToProvided;

    /**
     * @var string
     */
    protected $sendCc;

    /**
     * @var string
     */
    protected $sendCcProvided;

    /**
     * @var string
     */
    protected $sendBcc;

    /**
     * @var string
     */
    protected $sendBccProvided;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var array
     */
    protected $bodySlots = [];

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param string $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return bool
     */
    public function isSenderCustom()
    {
        return $this->senderCustom;
    }

    /**
     * @param bool $senderCustom
     */
    public function setSenderCustom($senderCustom)
    {
        $this->senderCustom = $senderCustom;
    }

    /**
     * @return string
     */
    public function getSendTo()
    {
        return $this->sendTo;
    }

    /**
     * @return array
     */
    public function getSendToProcessed()
    {
        return $this->recipientStringToArray($this->sendTo);
    }

    /**
     * @param string $sendTo
     */
    public function setSendTo($sendTo)
    {
        $this->sendTo = $sendTo;
    }

    /**
     * @return string
     */
    public function getSendToProvided()
    {
        return $this->sendToProvided;
    }

    /**
     * @return Email[]
     */
    public function getSelectedSendToProvided()
    {
        return $this->getSelectedProvidedRecipients($this->sendToProvided);
    }

    /**
     * @param Event $event
     * @return array
     */
    public function getSendToProvidedProcessed(Event $event)
    {
        return $this->mapRecipients(
            $this->recipientStringToArray($this->sendToProvided),
            $event->getProperties(Email::class)
        );
    }

    /**
     * @param string $sendToProvided
     */
    public function setSendToProvided($sendToProvided)
    {
        $this->sendToProvided = $sendToProvided;
    }

    /**
     * @return string
     */
    public function getSendCc()
    {
        return $this->sendCc;
    }

    /**
     * @return array
     */
    public function getSendCcProcessed()
    {
        return $this->recipientStringToArray($this->sendCc);
    }

    /**
     * @param string $sendCc
     */
    public function setSendCc($sendCc)
    {
        $this->sendCc = $sendCc;
    }

    /**
     * @return string
     */
    public function getSendCcProvided()
    {
        return $this->sendCcProvided;
    }

    /**
     * @return Email[]
     */
    public function getSelectedSendCcProvided()
    {
        return $this->getSelectedProvidedRecipients($this->sendCcProvided);
    }

    /**
     * @param Event $event
     * @return array
     */
    public function getSendCcProvidedProcessed(Event $event)
    {
        return $this->mapRecipients(
            $this->recipientStringToArray($this->sendCcProvided),
            $event->getProperties(Email::class)
        );
    }

    /**
     * @param string $sendCcProvided
     */
    public function setSendCcProvided($sendCcProvided)
    {
        $this->sendCcProvided = $sendCcProvided;
    }

    /**
     * @return string
     */
    public function getSendBcc()
    {
        return $this->sendBcc;
    }

    /**
     * @return array
     */
    public function getSendBccProcessed()
    {
        return $this->recipientStringToArray($this->sendBcc);
    }

    /**
     * @param string $sendBcc
     */
    public function setSendBcc($sendBcc)
    {
        $this->sendBcc = $sendBcc;
    }

    /**
     * @return string
     */
    public function getSendBccProvided()
    {
        return $this->sendBccProvided;
    }

    /**
     * @return Email[]
     */
    public function getSelectedSendBccProvided()
    {
        return $this->getSelectedProvidedRecipients($this->sendBccProvided);
    }

    /**
     * @param Event $event
     * @return array
     */
    public function getSendBccProvidedProcessed(Event $event)
    {
        return $this->mapRecipients(
            $this->recipientStringToArray($this->sendBccProvided),
            $event->getProperties(Email::class)
        );
    }

    /**
     * @param string $sendBccProvided
     */
    public function setSendBccProvided($sendBccProvided)
    {
        $this->sendBccProvided = $sendBccProvided;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return array
     */
    public function getBodySlots()
    {
        if (empty($this->bodySlots)) {
            /** @var FlexFormService $flexFormService */
            $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);

            $this->bodySlots = $flexFormService->convertFlexFormContentToArray($this->body);
        }

        return $this->bodySlots;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public static function getProcessorClassName()
    {
        return EntityEmailNotificationProcessor::class;
    }

    /**
     * @return string
     */
    public static function getSettingsClassName()
    {
        return EntityEmailSettings::class;
    }

    /**
     * @return string
     */
    public static function getNotificationIdentifier()
    {
        return 'entityEmail';
    }

    /**
     * This methods takes a comma or semi-colon separated list of recipients and
     * returns it as an array.
     *
     * @param $recipients
     * @return array
     */
    protected function recipientStringToArray($recipients)
    {
        $recipients = trim($recipients);
        $recipients = str_replace(',', ';', $recipients);
        $recipients = explode(';', $recipients);

        return $recipients;
    }

    /**
     * This method takes an array of recipient identifiers and returns the
     * desired mapped values.
     *
     * @param array $recipientsIdentifiers
     * @param Email[] $eventRecipients
     * @return array
     */
    protected function mapRecipients(array $recipientsIdentifiers, array $eventRecipients)
    {
        $recipients = [];

        /** @var EntityEmailSettings $settings */
        $settings = $this->getNotificationDefinition()->getSettings();

        foreach ($eventRecipients as $recipient) {
            if (in_array($recipient->getName(), $recipientsIdentifiers)) {
                $recipients[] = $recipient->getValue();
            }
        }

        foreach ($settings->getGlobalRecipients()->getRecipients() as $recipient) {
            if (in_array($recipient->getIdentifier(), $recipientsIdentifiers)) {
                $recipients[] = $recipient->getRawValue();
            }
        }

        return $recipients;
    }

    /**
     * @param string $providedRecipients
     * @return Email[]
     */
    protected function getSelectedProvidedRecipients($providedRecipients)
    {
        $providedRecipients = GeneralUtility::trimExplode(',', $providedRecipients);

        return array_filter(
            $this->getEmailProperties(),
            function (Email $email) use ($providedRecipients) {
                return in_array($email->getName(), $providedRecipients);
            }
        );
    }

    /**
     * @return PropertyEntry[]|Email[]
     */
    protected function getEmailProperties()
    {
        return $this->getEventDefinition()->getPropertiesDefinition(Email::class, $this);
    }
}
