<?php

namespace App\EventListener;

use App\Form\UserFormType;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Form\Event\PreSubmitEvent;

final class ClientFormListener
{
    #[AsEventListener(event: 'form.pre_submit')]
    public function onFormPreSubmit(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        $form = $event->getForm();
        if (isset($data["addAccount"]) && $data["addAccount"] == "1") {
            $form->add("account", UserFormType::class);
        }
    }
}
