<?php

namespace PostTypes\Shell;

use Cake\Console\Shell;
use Cake\Filesystem\File;

/**
 * Register shell command.
 */
class RegisterShell extends Shell
{

    public function initialize() {
        parent::initialize();

        $this->bootstrap = ROOT . DS . 'config' . DS . 'bootstrap.php';
    }

    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function main() {

        $_type = [];

        $this->name = $this->args[0];

        $_type['name'] = $this->name;
        $_type['model'] = $this->in("What model should we use?", null, $this->_generateModel());
        $_type['alias'] = $this->in("What alias should we use??", null, $this->args[0]);
        $_type['menu'] = (($this->in("Adding a menu-item for admin?", ['Y', 'N'], 'Y') == 'Y' ? 'true' : 'false'));
        $_type['api'] = (($this->in("Should we use the built in API?", ['Y', 'N'], 'N') == 'Y' ? 'true' : 'false'));
        $_type['tableFields'] = explode(', ', $this->in("Give a comma-seperated string with the fields for the table", null, null));
        $_type['formFields'] = explode(', ', $this->in("Give a comma-seperated string with the fields for the forms", null, null));

        debug($_type);

        $this->_modifyBootstrap($this->name, $_type);
    }

    public function getOptionParser() {
        $parser = parent::getOptionParser();

        $parser->addOption('plugin', [
            'short'   => 'p',
            'help'    => "The plugin's name",
            'default' => false,
        ]);

        $parser->addArgument('name', [
            'required' => true,
            'help'     => __('The name of your PostType'),
        ]);

        return $parser;
    }

    protected function _generateModel() {

        $name = '';

        if ($this->params['plugin']) {
            $name .= $this->params['plugin'] . ".";
        }

        $name .= $this->args[0];

        return $name;
    }

    protected function _modifyBootstrap($name, $_type) {
        $bootstrap = new File($this->bootstrap, false);
        $contents = $bootstrap->read();

        $bootstrap->append(sprintf("\n \n
Configure::write('PostTypes.Register.%s', [
    'model'       => '%s',
    'alias'       => '%s',
    'menu'        => %s,
    'api'         => %s,
    'tableFields' => [
        '%s'
    ],
    'formFields'  => [
        '%s'
    ]
]); \n", $_type['name'], $_type['model'], $_type['alias'], $_type['menu'], $_type['api'], implode("', \n \t \t'",$_type['tableFields']), implode("', \n \t \t'",$_type['formFields'])
        ));
        $this->out('');
        $this->out(sprintf('%s modified', $this->bootstrap));
    }

}
