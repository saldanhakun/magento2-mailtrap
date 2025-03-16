<?php

namespace Saldanhakun\Mailtrap\Mail;

use Closure;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\EmailMessageInterface;
use Magento\Framework\Mail\Message;
use Magento\Framework\Mail\TransportInterface;
use Saldanhakun\Mailtrap\Helper\Data;
use Saldanhakun\Mailtrap\Mail\SmtpFactory;
use Saldanhakun\Mailtrap\Mail\Smtp;
use Saldanhakun\Mailtrap\Model\Store;

class TransportPlugin
{

    /**
     * @var Data
     */
    protected Data $dataHelper;

    /**
     * @var Store
     */
    protected $storeModel;

    /**
     * @var Smtp
     */
    private SmtpFactory $smtpFactory;

    /**
     * @param Data $dataHelper
     * @param Store $storeModel
     * @param SmtpFactory $smtpFactory
     */
    public function __construct(
        Data $dataHelper,
        Store $storeModel,
        SmtpFactory $smtpFactory
    ) {
        $this->dataHelper = $dataHelper;
        $this->storeModel = $storeModel;
        $this->smtpFactory = $smtpFactory;
    }

    /**
     * @param TransportInterface $subject
     * @param Closure $proceed
     * @throws MailException
     */
    public function aroundSendMessage(
        TransportInterface $subject,
        Closure $proceed
    ) {
        if ($this->dataHelper->isActive()) {
            if (method_exists($subject, 'getStoreId')) {
                $this->storeModel->setStoreId($subject->getStoreId());
            }

            $message = $subject->getMessage();

            if ($message instanceof Message || $message instanceof EmailMessageInterface) {
                /** @var Smtp $smtp */
                $smtp = $this->smtpFactory->create(
                    ['dataHelper' => $this->dataHelper, 'storeModel' => $this->storeModel]
                );
                $smtp->sendSmtpMessage($message);
            } else {
                $proceed();
            }
        } else {
            $proceed();
        }
    }
}
