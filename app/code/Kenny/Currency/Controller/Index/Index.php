<?php
namespace Kenny\Currency\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    public function execute()
    {
        $layout = $this->_view->getLayout();
        $block = $layout->createBlock('Magento\Framework\View\Element\Text');
        $uri = 'http://free.currencyconverterapi.com/api/v5/convert?q=USD_VND&compact=y';
        $httpClient = new \Zend\Http\Client();
        $httpClient->setUri($uri);
        $httpClient->setOptions(array(
            'timeout' => 30
        ));
        try {
            $response = \Zend\Json\Decoder::decode($httpClient->send()->getBody());
            if (isset($response->USD_VND) && isset($response->USD_VND->val)) {
                $block->addText(' 1 USD = ' . $response->USD_VND->val . ' VND');
            }
        } catch (\Exception $e) {
            $block->setText($e->getMessage());
        }
        $this->getResponse()->appendBody($block->toHtml());
    }
}
