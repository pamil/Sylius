<?php

namespace Sylius\Bundle\CoreBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Core\Model\CountryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
class CountryController extends ResourceController
{
    public function enableAction(Request $request)
    {
        /** @var CountryInterface $country */
        $country = $this->findOr404($request);
        $country->setEnabled(true);

        $this->domainManager->update($country, 'enable');

        return $this->redirect($this->generateUrl('sylius_backend_country_index'));
    }

    public function disableAction(Request $request)
    {
        /** @var CountryInterface $country */
        $country = $this->findOr404($request);
        $country->setEnabled(false);

        $this->domainManager->update($country, 'disable');

        return $this->redirect($this->generateUrl('sylius_backend_country_index'));
    }
}
