<?php

/*
 * This file is part of the SDA package
 *
 * Copyright (c) 2020-2024 STRONGHOLD ASSET MANAGEMENT
 * All right reserved
 *
 * @author Álvaro Cebrián <acebrian@strongholdam.com>
 * @author Daniel González <dgonzalez@strongholdam.com>
 * @author Raúl Callado <rcallado@strongholdam.com>
 */

namespace Strongholdam\Microsoft\OneDrive\Command;

use Strongholdam\Microsoft\OneDrive\OneDriveService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'strongholdam:microsoft:one-drive:upload', description: 'Push a file to onedrive')]
class UploadCommand extends Command
{
    public function __construct(private readonly OneDriveService $oneDriveService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('localPath', InputArgument::REQUIRED)
            ->addArgument('remotePath', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $localPath = $input->getArgument('localPath');
        $remotePath = $input->getArgument('remotePath');

        try {
            $this->oneDriveService->push($localPath, $remotePath);
            $io->success(sprintf("File uploaded successfully"));
        } catch (\Exception) {
            $io->error(sprintf("Error pushing file"));
        }

        return Command::SUCCESS;
    }
}
