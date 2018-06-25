<?php
/**
 *
 * @version $Id: cron.php 1256 2011-04-28 06:26:44Z boopathi_026ac09 $
 * @copyright 2009
 */
class CronShell extends Shell
{
    public function main()
    {
		// site settings are set in config
        App::import('Model', 'Setting');
        $setting_model_obj = new Setting();
        $settings = $setting_model_obj->getKeyValuePairs();
        Configure::write($settings);
		// include cron component
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'cron');
        $this->Cron = new CronComponent($collection);
        $option = !empty($this->args[0]) ? $this->args[0] : '';
        $this->log('Cron started without any issue');
        if (!empty($option) && $option == 'main') {
            $this->Cron->main();
        }
    }
}
?>