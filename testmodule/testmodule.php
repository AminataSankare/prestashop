<?php

include_once dirname(__FILE__) . '/webservice/WebserviceManagement.php';

if (!defined('_PS_VERSION_')) {
    exit;
}

if (!class_exists('Customer')) {
    include_once _PS_CLASS_DIR_.'/../classes/Customer.php';
}

class Testmodule extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'testmodule';
        $this->tab = 'pricing_promotion';
        $this->version = '1.7.1';
        $this->author = 'Aminata Sankare';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Send Birthday Reduction');
        $this->description = $this->l('Reduction du prix le jour des anniversaires');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        Configuration::updateValue('TESTMODULE_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('actionAdminControllerSetMedia') &&
            $this->registerHook('actionAdminPerformanceControllerSaveAfter') &&
            $this->registerHook('actionModuleRegisterHookAfter') &&
            $this->registerHook('displayAdminCustomers');
    }

    public function uninstall()
    {
        Configuration::deleteByName('TESTMODULE_LIVE_MODE');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        $output = null;
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitTestmoduleModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->toolbar_btn = [
            'save' => [
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
            ],
            'back' => [
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        ];
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'TESTMODULE_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'html',
                        'name' => 'TESTMODULE_ACCOUNT_DAY_BEFORE',
                        'label' => $this->l('Nombre de jour avant'),
                        'html_content' => '<input type="number" name="TESTMODULE_ACCOUNT_DAY_BEFORE">',
                        'required' => true
                    ),
                    array(
                        'type' => 'html',
                        'name' => 'TESTMODULE_ACCOUNT_OFFER_DURATION',
                        'label' => $this->l('Durée du bon'),
                        'html_content' => '<input type="number" name="TESTMODULE_ACCOUNT_OFFER_DURATION">',
                        'required' => true

                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'TESTMODULE_LIVE_MODE' => Configuration::get('TESTMODULE_LIVE_MODE', true),
            'TESTMODULE_ACCOUNT_DAY_BEFORE' => Configuration::get('TESTMODULE_ACCOUNT_DAY_BEFORE', 3),
            'TESTMODULE_ACCOUNT_OFFER_DURATION' => Configuration::get('TESTMODULE_ACCOUNT_OFFER_DURATION', 7),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
        return $form_values;

    }

    /**
     * Get user to send mail with form data.
     */  
    public function userToSendMail($day_before, $duration){
        
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }


}
