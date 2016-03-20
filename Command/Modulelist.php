<?php

namespace Osrecio\CommandModuleList\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\Table;

use Magento\Framework\Module\FullModuleList;
use Magento\Framework\Module\Manager as ModuleManager;

class Modulelist extends Command
{
    /**
     * Full Module list
     *
     * @var FullModuleList
     */
    private $fullModuleList;
    /**
     * Module Manager
     *
     * @var ModuleManager
     */
    private $moduleManager;

    /**
     * Listmodule constructor
     * @param FullModuleList $moduleList
     * @param ModuleManager $moduleManager
     */
    public function __construct(FullModuleList $fullModuleList, ModuleManager $moduleManager)
    {
        $this->fullModuleList = $fullModuleList;
        $this->moduleManager = $moduleManager;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName("module:list")
            ->setDescription("List all modules")
            ->addOption(
                'vendor',
                'vend',
                InputOption::VALUE_OPTIONAL,
                'If set, only the vendor modules are displayed'
            )
            ->addOption(
                'enabled',
                'en',
                InputOption::VALUE_OPTIONAL,
                "If set, only enabled or disabled modules are displayed, <comment>Possible values: ('y' or 'n')</comment>"
            )
        ;
        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* Get Option vendor */
        $inputVendor = $input->getOption('vendor');
        if ($inputVendor) {
            $preg = '/' . $inputVendor . '_(.*)/i';
            $listModules = array_flip(preg_grep($preg, $this->fullModuleList->getNames()));
        } else {
            $listModules = array_flip($this->fullModuleList->getNames());
        }

        /* Get Option enabled */
        $inputEnabled = $input->getOption('enabled');
        if($inputEnabled){
            if($inputEnabled != 'n' && $inputEnabled != 'y'){
                $output->writeln("<error>--enabled only can be 'y' or 'n'</error>");
                die;
            }
        }
        $modules = $this->getModulesByCriteria($listModules, $inputEnabled);

        if (count($modules)) {
            $this->ouputHeader($output);
            $this->ouputResult($output, $modules);
        } else {
            $output->writeln('<error>Not exist modules with these criteria</error>');
        }

    }

    /**
     * Get Filtered Modules by Options selected
     * @param $moduleList
     * @param $inputEnabled
     * @return array
     */
    protected function getModulesByCriteria($moduleList, $inputEnabled)
    {
        $moduleInfo = array();
        $modules = array_intersect_key($this->fullModuleList->getAll(), $moduleList);

        foreach ($modules as $module) {
            $vendor = current(explode("_", $module['name']));
            $title = $module['name'];
            $version = $module['setup_version'];
            $enabled = $this->moduleManager->isEnabled($title) ? 'Y' : 'N';
            $outputEnabled = $this->moduleManager->isOutputEnabled($title) ? 'Y' : 'N';
            if ($inputEnabled) {
                if($inputEnabled == strtolower($enabled)){
                    $moduleInfo[] = array($vendor, $title, $version, $enabled, $outputEnabled);
                }
            }else{
                $moduleInfo[] = array($vendor, $title, $version, $enabled, $outputEnabled);
            }
        }
        return $moduleInfo;
    }

    /**
     * @param OutputInterface $output
     * @param $modules
     */
    protected function ouputResult(OutputInterface $output, $modules)
    {
        $table = new Table($output);

        $table->setHeaders(array('Vendor', 'Title', 'Version', 'Is Enabled', 'Is Output Enabled'));
        $table->addRows($modules);
        $table->render();
    }

    /**
     * @param OutputInterface $output
     */
    protected function ouputHeader(OutputInterface $output)
    {
        $output->writeln('');
        $output->writeln('<question>List of modules</question>');
        $output->writeln('');
    }
}