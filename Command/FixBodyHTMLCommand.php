<?PHP

namespace TheCodeine\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TheCodeine\NewsBundle\Entity\News;
use TheCodeine\PageBundle\Entity\Page;

class FixBodyHTMLCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('tuna:admin:fix-body-html')
            ->setDescription('Check and correct all body HTML in pages and news items');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $pr = $em->getRepository(Page::class);
        $nr = $em->getRepository(News::class);

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

    /**
     * @param $html
     *
     * @return mixed
     */
    private function fixHTML($html) {
        if($html != '') {
            $html  = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $html);
            $html  = preg_replace('/(<[^>]+) color=".*?"/i', '$1', $html);
            $html  = preg_replace('/(<[^>]+) face=".*?"/i', '$1',  $html);
        }

        return $html;
    }
}