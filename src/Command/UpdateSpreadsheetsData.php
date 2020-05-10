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

class UpdateSpreadsheetsData extends Command
{
    protected static $defaultName = 'spreadsheets:update';

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
            ->setDescription('Update spreadsheets data')
            ->addArgument('sheetId', InputArgument::REQUIRED, 'sheetId')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $history = $this->historyRepository->get();

        $body = new \Google_Service_Sheets_ValueRange([
            'values' => $history->getItems(),
        ]);

        $params = [
            'valueInputOption' => 'RAW'
        ];

        $response = $this->googleApiClientProvider->sheets()->spreadsheets_values
            ->update($input->getArgument('sheetId'), 'A1:K', $body, $params);

        /*
        SheetID,
        ID operacji,
        data wykonania,
        czas trwania,
        użyta pamięć,
        status operacji,
        ilość przetworzonych wierszy
           */
        dd(
            $response->toSimpleObject(),
            $response->getSpreadsheetId(),
            $response->getUpdatedCells()
        );
    }
}