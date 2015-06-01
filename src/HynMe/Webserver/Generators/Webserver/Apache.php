<?php namespace HynMe\Webserver\Generators\Webserver;

use Config;
use HynMe\Webserver\Generators\AbstractFileGenerator;

class Apache extends AbstractFileGenerator
{

    /**
     * Generates the view that is written
     * @return \Illuminate\View\View
     */
    public function generate()
    {
        return view('webserver::webserver.apache.configuration', [
            'website' => $this->website,
            'public_path' => public_path(),
            'log_path' => base_path("log/apache-{$this->website->id}-{$this->website->identifier}"),
            'base_path' => base_path(),
            'config' => Config::get('webserver.apache')
        ]);
    }

    /**
     * Provides the complete path to publish the generated content to
     * @return string
     */
    protected function publishPath()
    {
        return sprintf("%s%s.conf", Config::get('webserver.paths.apache'), $this->name());
    }

    /**
     * Reloads service if possible
     *
     * @return bool
     */
    protected function serviceReload()
    {
        $ret = exec("apache2ctl -t", $out, $state);

        if($ret == "Syntax OK" || $state === 0)
            exec("service apache2 reload", $out, $state);

        return true;
    }
}