<?php

namespace App\Command;

use App\Cache\CloudFlare;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AppCloudflareClearCacheCommand extends Command
{
    protected static $defaultName = 'app:cloudflare:clear-cache';

    /**
     * @var CloudFlare
     */
    private $service;

    public function __construct(CloudFlare $service)
    {
        $this->service = $service;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Clear cache on the CloudFlare CDN')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->service->purgeCache();
            $io->success('Cache cleared !');
        } catch (Exception $e) {
            $io->error('Cache clear : failed !');
        }
    }
}
