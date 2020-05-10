<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\History;
use App\Service\GoogleApiClient\GoogleApiClientProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Repository\HistoryJsonFileRepository;
use Google_Service_Exception;

class GetSpreadsheetsData extends Command
{
    protected static $defaultName = 'spreadsheets:get-and-save';

    private GoogleApiClientProvider $googleApiClientProvider;

    private HistoryJsonFileRepository $historyRepository;

    public function __construct(
        GoogleApiClientProvider $googleApiClientProvider,
        HistoryJsonFileRepository $historyRepository,
        string $name = null
    )
    {
        parent::__construct($name);

        $this->googleApiClientProvider = $googleApiClientProvider;
        $this->historyRepository = $historyRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Get data form google spreadsheets and save it to the local database.')
            ->addArgument('sheetId', InputArgument::REQUIRED, 'sheetId')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $response = $this->googleApiClientProvider->sheets()->spreadsheets_values->get(
                $input->getArgument('sheetId'),
                'Sheet1'
            );

            $history = new History();

            foreach ($response->getValues() as $value) {
                $history->addItem($value);
            }

            $this->historyRepository->save($history);

            $output->writeln('<info>Data has been saved</info>');

        } catch (Google_Service_Exception $exception) {
            $output->writeln(sprintf("<error>%s</error>", $exception->getMessage()));
        }
        
        return 1;
    }
}