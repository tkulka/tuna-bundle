<?PHP

namespace TheCodeine\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FixBodyHTMLCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        ->setName('thecodeine:admin:fix-body-html')
        ->setDescription('Check and correct all body HTML in pages and news items')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $pr = $em->getRepository('TheCodeinePageBundle:Page');
        $nr = $em->getRepository('TheCodeineNewsBundle:News');

        foreach(array_merge($pr->findAll(), $nr->findAll()) as $item) {
            $itemTitle = $item->getTitle();
            if (!$item->getBody()) {
                foreach($item->getTranslations() as $ik) {
                    if($ik->getContent()) {
                        $item->setLocale('en');
                        $em->refresh($item);

                        $title = $itemTitle ? $itemTitle : $item->getTitle() ;
                        $body = $item->getBody();

                        $item->setLocale('pl');
                        $em->refresh($item);

                        $item->setTitle($title);
                        $item->setBody($body);
                    }
                }
            }
            $item->setBody(
                $this->fixHTML($item->getBody())
            );
            $em->persist($item);
            $em->flush();
        }

    }

    private function fixHTML($html) {
        if($html != '') {
            $html  = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $html);
            $html  = preg_replace('/(<[^>]+) color=".*?"/i', '$1', $html);
            $html  = preg_replace('/(<[^>]+) face=".*?"/i', '$1',  $html);
        }
        return $html;
    }
}