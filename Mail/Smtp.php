<?php

namespace Saldanhakun\Mailtrap\Mail;

use Exception;
use Laminas\Mail\AddressList;
use Laminas\Mail\Header\HeaderInterface;
use Laminas\Mail\Message;
use Laminas\Mail\Transport\Smtp as SmtpTransport;
use Laminas\Mail\Transport\SmtpOptions;
use Laminas\Mime\Mime;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\EmailMessageInterface;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Phrase;
use Saldanhakun\Mailtrap\Helper\Data;
use Saldanhakun\Mailtrap\Model\Store;

/**
 * Class Smtp
 */
class Smtp
{
    /**
     * @var Data
     */
    protected $dataHelper;

    /**
     * @var Store
     */
    protected $storeModel;

    /**
     * @param Data $dataHelper
     * @param Store $storeModel
     */
    public function __construct(
        Data $dataHelper,
        Store $storeModel
    ) {
        $this->dataHelper = $dataHelper;
        $this->storeModel = $storeModel;
    }

    /**
     * @param Data $dataHelper
     * @return Smtp
     */
    public function setDataHelper(Data $dataHelper)
    {
        $this->dataHelper = $dataHelper;
        return $this;
    }

    /**
     * @param Store $storeModel
     * @return Smtp
     */
    public function setStoreModel(Store $storeModel)
    {
        $this->storeModel = $storeModel;
        return $this;
    }

    /**
     * @param $message
     * @return Message
     */
    protected function convertMessage($message)
    {
        $encoding = 'utf-8';

        try {
            $reflect = new \ReflectionClass($message);
            $zendMessageObject = $reflect->getProperty('zendMessage');
            $zendMessageObject->setAccessible(true);

            /** @var Message $zendMessage */
            $zendMessage =  $zendMessageObject->getValue($message);

            if ($message instanceof EmailMessageInterface) {
                $encoding = $message->getEncoding();
            } else {
                $encoding = $zendMessage->getEncoding();
            }

            if (!$zendMessage instanceof Message) {
                throw new MailException('Not instance of Message');
            }
        } catch (Exception $e) {
            $zendMessage = Message::fromString($message->getRawMessage());
        }

        $zendMessage->setEncoding($encoding);

        return $zendMessage;
    }

    /**
     * @param MessageInterface | EmailMessageInterface $message
     * @throws MailException
     */
    public function sendSmtpMessage(
        $message
    ) {
        $dataHelper = $this->dataHelper;
        $dataHelper->setStoreId($this->storeModel->getStoreId());

        /** @var Message $message */
        $message = $this->convertMessage($message);

        $this->setReplyToPath($message);
        $this->setSender($message);

        foreach ($message->getHeaders()->toArray() as $headerKey => $headerValue) {
            $mailHeader = $message->getHeaders()->get($headerKey);
            if ($mailHeader instanceof HeaderInterface) {
                $this->updateMailHeader($mailHeader);
            } elseif ($mailHeader instanceof \ArrayIterator) {
                foreach ($mailHeader as $header) {
                    $this->updateMailHeader($header);
                }
            }
        }

        try {
            $transport = new SmtpTransport();
            $transport->setOptions($this->getSmtpOptions());
            $transport->send($message);
        } catch (Exception $e) {
            throw new MailException(
                new Phrase($e->getMessage()),
                $e
            );
        }
    }

    /**
     *
     * @param Message $message
     */
    protected function setSender($message)
    {
        $dataHelper = $this->dataHelper;
        //Set from address
        $setFromEmail = $message->getFrom()->count() ? $message->getFrom() : $this->getFromEmailAddress();

        if ($setFromEmail !== null) {
            if (is_string($setFromEmail)) {
                $name = $this->getFromName();
                $message->setFrom(trim($setFromEmail), $name);
                $message->setSender(trim($setFromEmail), $name);
            } elseif ($setFromEmail instanceof AddressList) {
                foreach ($setFromEmail as $address) {
                    $message->setFrom($address);
                    $message->setSender($address);
                }
            }
        }

        if (!$message->getFrom()->count()) {
            $result = $this->storeModel->getFrom();
            $message->setFrom($result['email'], $result['name']);
        }
    }

    /**
     * @param Message $message
     */
    protected function setReplyToPath($message)
    {
        $dataHelper = $this->dataHelper;

        //Set reply-to path
        $returnPathEmail = $message->getFrom()->count() ? $message->getFrom() : $this->getFromEmailAddress();

        if (!$message->getReplyTo()->count()) {
            if (is_string($returnPathEmail)) {
                $name = $this->getFromName();
                $message->setReplyTo(trim($returnPathEmail), $name);
            } elseif ($returnPathEmail instanceof AddressList) {
                foreach ($returnPathEmail as $address) {
                    $message->setReplyTo($address);
                }
            }
        }
    }

    /**
     * @return SmtpOptions
     */
    protected function getSmtpOptions()
    {
        $dataHelper = $this->dataHelper;

        //set config
        $options   = new SmtpOptions([
            'name' => "mailtrap",
            'host' => $dataHelper->getConfigSmtpHost(),
	    'port' => $dataHelper->getConfigSmtpPort(),
        ]);

	$connectionConfig = [];

        $auth = strtolower($dataHelper->getConfigAuth());
        if ($auth != 'none') {
            $options->setConnectionClass($auth);

            $connectionConfig = [
                'username' => $dataHelper->getConfigUsername(),
		'password' => $dataHelper->getConfigPassword(),
		'ssl' => 'tls',
            ];
        }

        if (!empty($connectionConfig)) {
            $options->setConnectionConfig($connectionConfig);
        }

        return $options;
    }

    /**
     * @param $header
     */
    public function updateMailHeader($header)
    {
        if ($header instanceof HeaderInterface) {
            if (Mime::isPrintable($header->getFieldValue())) {
                $header->setEncoding('ASCII');
            } else {
                $header->setEncoding('utf-8');
            }
        }
    }

    /**
     * @return string
     */
    public function getFromEmailAddress()
    {
        $result = $this->storeModel->getFrom();
        return isset($result['email']) ? $result['email'] : '';
    }

    /**
     * @return string
     */
    public function getFromName()
    {
        $result = $this->storeModel->getFrom();
        return isset($result['name']) ? $result['name'] : '';
    }
}
