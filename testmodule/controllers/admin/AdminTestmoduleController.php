<?php

class AdminZakekeController extends ModuleAdminController
{
    public function ajaxProcessInfo()
    {
        $id_zakeke = Tools::getValue('id_zakeke', false);

        if ($id_zakeke === false) {
            $this->errors[] = $this->module->l('id_zakeke is null or empty');
        }

        try {
            /** @var Zakeke $zakeke */
            $zakeke = $this->module;
            $zipUrl = '';
            try {
                $zipUrl = $zakeke->getZakekeApi()->getZakekeOutputZip($id_zakeke);
            } catch (Exception $e) {
                $this->errors[] = $this->module->l('Zakeke cant get the zip file');
                $id_order = Tools::getValue('id_order', false);
                if ($id_order) {
                    $order = new Order($id_order);
                    if (Validate::isLoadedObject($order)) {
                        $zakekeOrderService = new ZakekeOrderService($zakeke);
                        $zakekeOrderService->process($order);
                    }
                }
            }

            $id_zakeke_item = ZakekeItem::zakekeItemId($id_zakeke);
            if ($id_zakeke_item === false) {
                $this->errors[] = $this->module->l('Zakeke item not found');
            }
            $zakeke_item = new ZakekeItem($id_zakeke_item);
        } catch (Exception $e) {
            PrestaShopLogger::addLog('Zakeke AdminZakekeController::ajaxProcessInfo Call error: ' . $e);
            $this->errors[] = $this->module->l('Failed to get the design output');
        }

        if (!$this->errors) {
            die(Tools::jsonEncode(array(
                'zipUrl' => $zipUrl,
                'preview' => $zakeke_item->preview
            )));
        } else {
            die(Tools::jsonEncode(array(
                'hasError' => true,
                'errors' => $this->errors
            )));
        }
    }
}
