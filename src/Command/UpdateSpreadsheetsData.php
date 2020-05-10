<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\GoogleApiClient\GoogleApiClientProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Repository\HistoryJsonFileRepository;
use Google_Service_Exception;
use Symfony\Component\Stopwatch\Stopwatch;

class UpdateSpreadsheetsData extends Command
{
    protected static $defaultName = 'spreadsheets:update';

    private GoogleApiClientProvider $googleApiClientProvider;

    private HistoryJsonFileRepository $historyRepository;

    private Stopwatch $stopwatch;

    public function __construct(
        GoogleApiClientProvider $googleApiClientProvider,
        HistoryJsonFileRepository $historyRepository,
        Stopwatch $stopwatch,
        string $name = null
    )
    {
        parent::__construct($name);

        $this->googleApiClientProvider = $googleApiClientProvider;
        $this->historyRepository = $historyRepository;
        $this->stopwatch = $stopwatch;
    }

    protected function configure()
    {
        $this
            ->setDescription('Update spreadsheets data')
            ->addArgument('sheetId', InputArgument::REQUIRED, 'sheetId')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->stopwatch->start(self::$defaultName);

            $history = $this->historyRepository->get();

            $body = new \Google_Service_Sheets_ValueRange([
                'values' => $history->getItems(),
            ]);

            $params = [
                'valueInputOption' => 'RAW'
            ];

            $response = $this->googleApiClientProvider->sheets()->spreadsheets_values
                ->update($input->getArgument('sheetId'), 'A1:K', $body, $params);

            $event = $this->stopwatch->stop(self::$defaultName);

            $output->writeln([
                sprintf("SheetID: %s", $response->getSpreadsheetId()),
                sprintf("Operation id: %s", '?'),
                sprintf("Execution date: %s", (new \DateTime())->format("Y-m-d h:i:s")),
                sprintf("Execution process time: %s", $event->getEndTime() - $event->getStartTime()),
                sprintf("Memory used: %s", $event->getMemory()),
                sprintf("Status: %s", 'Success'),
                sprintf("Updated cells: %s", $response->getUpdatedCells()),
            ]);
        } catch (Google_Service_Exception $exception) {
            $output->writeln(sprintf("<error>%s</error>", $exception->getMessage()));
        }

        return 1;
    }
}