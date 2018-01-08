<?php

namespace OGIVE\AlertBundle\Command;

//use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use OGIVE\AlertBundle\Entity\AlertProcedure;
use OGIVE\AlertBundle\Entity\Owner;

/**
 * Description of ExtractAndSaveOwnerCommand
 *
 * @author Eric TONYE
 */
class ExtractAndSaveOwnerCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                // the name of the command (the part after "app/console")
                ->setName('app:extract-and-save-owners')

                // the short description shown while running "php app/console list"
                ->setDescription('Extact owner from call offer, etc... and save it in owner table')
                
                ->addArgument('number',InputArgument::OPTIONAL,'In which do you want to extract owner?')
                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp('This command allows you to extract all owner which a suscription is  out to date...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $number = $input->getArgument('number');
        if ($number && $number == "2") {
            $repositoryProcedure = $this->getContainer()->get('doctrine')->getEntityManager()->getRepository('OGIVEAlertBundle:ExpressionInterest');
        } else {
            $repositoryProcedure = $this->getContainer()->get('doctrine')->getEntityManager()->getRepository('OGIVEAlertBundle:CallOffer');
        }
        $repositoryOwner = $this->getContainer()->get('doctrine')->getEntityManager()->getRepository('OGIVEAlertBundle:Owner');
        $procedure = new AlertProcedure();
        $procedures = $repositoryProcedure->findBy(array('status' => 1, 'state' => 1));
        foreach ($procedures as $procedure) {
            $owner = new Owner();
            $owner->setName($procedure->getOwner());
            $owner->setNumero(strtolower($procedure->getOwner()));
            $ownerUnique = $repositoryOwner->findBy(array('numero' => $owner->getNumero(), 'status' => 1, 'state' => 1));
            if($ownerUnique== null && $owner){
                $output->writeln($procedure->getOwner());
                $repositoryOwner->saveOwner($owner);
            }
        }
        $output->writeln('Taches Cron Terminées avec succès');
    }

    
}
