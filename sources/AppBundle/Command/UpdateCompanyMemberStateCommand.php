<?php

declare(strict_types=1);

namespace AppBundle\Command;

use AppBundle\Association\Model\CompanyMember;
use AppBundle\Association\Model\Repository\CompanyMemberRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCompanyMemberStateCommand extends Command
{
    public function __construct(
        private readonly CompanyMemberRepository $companyMemberRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('update-company-member-state')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var CompanyMember $companyMember */
        foreach ($this->companyMemberRepository->loadAll() as $companyMember) {
            $hasUptoDateMembershipFee = $companyMember->hasUpToDateMembershipFee();
            $companyMember->setStatus($hasUptoDateMembershipFee ? 1 : 0);
            $this->companyMemberRepository->save($companyMember);
        }

        return Command::SUCCESS;
    }
}
