<?php

declare(strict_types=1);


namespace App\Command;

use App\Service\GoogleApiClient\GoogleApiClientProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

class GetSpreadsheetsData extends Command
{
    protected static $defaultName = 'spreadsheets:get';

    private GoogleApiClientProvider $googleApiClientProvider;

    public function __construct(GoogleApiClientProvider $googleApiClientProvider, string $name = null)
    {
        parent::__construct($name);

        $this->googleApiClientProvider = $googleApiClientProvider;
    }

    protected function configure()
    {
        $this
            ->setDescription('Get data form google spreadsheets.')
            ->addArgument('sheetId', InputArgument::REQUIRED, 'sheetId')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Sheet id: '.$input->getArgument('sheetId'));

        $response = $this->googleApiClientProvider->sheets()->spreadsheets_values->get(
            $input->getArgument('sheetId'),
            'Sheet1'
        );

//        dd($response->getValues());

        return 1;
    }
}