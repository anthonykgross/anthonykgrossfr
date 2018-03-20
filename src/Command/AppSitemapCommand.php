<?php

namespace App\Command;

use App\Sitemap\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppSitemapCommand extends Command
{
    protected static $defaultName = 'app:sitemap';

    /**
     * @var Generator
     */
    private $generator;


    public function __construct(Generator $generator)
    {
        $this->generator = $generator;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Generate sitemap.xml')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->generator->generate();
    }
}
