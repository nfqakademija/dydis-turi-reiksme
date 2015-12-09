<?php

namespace DTR\CrawlerBundle\Command;

use DTR\CrawlerBundle\Services\Helpers\PopulatorInterface;
use DTR\DTRBundle\Entity\Product;
use DTR\DTRBundle\Entity\Shop;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class CrawlerCommand extends ContainerAwareCommand
{
    protected $asker;

    protected $crawler;

    protected $engine;

    protected $url;

    protected $name;

    protected $directory;

    protected $filename;

    protected $edit = false;

    protected $number_of_lines;

    protected $populator;

    /**
     *
     * Public constructor
     * @param null|string $crawler_str
     * @param $engine_str
     * @param $directory
     * @param $number_of_lines
     * @param PopulatorInterface $populator
     */
    public function __construct($crawler_str, $engine_str, $directory, $number_of_lines, $populator)
    {
        $this->crawler = $crawler_str;
        $this->engine = $engine_str;
        $this->directory = $directory;
        $this->number_of_lines = $number_of_lines;
        $this->populator = $populator;

        parent::__construct();
    }

    /**
     * Initialize
     */
    protected function configure()
    {
        $this
            ->setName('dtr:crawler')
            ->setDescription('Crawl menu data from url.')
            ->addOption('url', 'u', InputOption::VALUE_REQUIRED, 'The page url where a menu is located.')
            ->addOption('name', null, InputOption::VALUE_REQUIRED, 'The shop name.')
            ->addOption('directory', 'dir', InputOption::VALUE_REQUIRED, 'Name of directory to write result file to.')
            ->addOption('print', 'p', InputOption::VALUE_OPTIONAL, 'Print contents to screen.', false)
            ->addOption('engine', 'eng', InputOption::VALUE_REQUIRED, 'The engine to use for menu lookup.', $this->engine);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * Init some variables
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->asker = $this->getHelper('question');
        $directory = $input->getOption('directory');

        if(isset($directory))
        {
            $this->directory = getcwd(). '/'. $directory;
            $this->edit = true;
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * Take tour
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $this->url = $this->manageUrlInput($input, $output);
        $this->name = $this->manageNameInput($input, $output);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     *
     * Run Crawler
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $pattern = $container->get($this->engine);

        $crawler = $container->get($this->crawler);
        $crawler->setPattern($pattern);

        $output->writeln('<fg=green>Fetching data from</> '. $this->url);
        $menu = $crawler->getMenu($this->url);
        $output->writeln('<fg=green>Data fetched!</>');

        $this->filename = $this->name. '-'. date('Y-m-d_H-i-s'). '.json';
        $this->writeToFile($menu);

        $this->manageSnippet($input, $output);

        if(!$this->edit)
            $this->manageEditFile($input, $output);

        if($this->edit)
        {
            $new_menu = $this->editFile($input, $output);

            if($new_menu !== false)
                $menu = $new_menu;
        }

        $em = $container->get('doctrine.orm.entity_manager');

        unlink($this->directory. '/'. $this->filename);
        $this->populateMenu($menu, $this->name, $em);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    private function manageUrlInput(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getOption('url');

        if(isset($url))
            return $url;

        $question = new Question('<fg=cyan>Enter a menu url</>: ');
        $question->setValidator(function($url) {
            if(!filter_var($url, FILTER_VALIDATE_URL))
                throw new \RuntimeException('The supplied url is not a valid url.');

            return $url;
        });

        return $this->asker->ask($input, $output, $question);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    private function manageNameInput(InputInterface $input, OutputInterface $output)
    {
        $title = $input->getOption('name');

        if(isset($title))
            return $title;

        $question = new Question('<fg=cyan>Enter shop name</>: ');
        $question->setValidator(function($name) {
            if(empty($name))
                throw new \RuntimeException('The supplied name is empty.');

            return $name;
        });

        return $this->asker->ask($input, $output, $question);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function manageEditFile(InputInterface $input, OutputInterface $output)
    {
        $question = new ConfirmationQuestion('<fg=cyan>Do you want to edit results?</> <fg=yellow>[no]</>: ', false);

        if(!$this->asker->ask($input, $output, $question))
            return;

        $question = new Question('<fg=cyan>Specify directory</> <fg=yellow>['. str_replace(getcwd(), '', $this->directory). '/]</>: ', $this->directory);
        $question->setValidator(function($directory) {
            $old_location = $this->directory. '/'. $this->filename;
            $new_location = $directory. '/'. $this->filename;

            if($old_location != $new_location)
            {
                $success = rename($old_location, $new_location);

                if(!$success)
                    throw new \RuntimeException('Could not move file from '. $old_location. ' to '. $new_location);
            }

            return $directory;
        });

        $this->directory = $this->asker->ask($input, $output, $question);
        $this->edit = true;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    private function manageSnippet(InputInterface $input, OutputInterface $output)
    {
        $question = new ConfirmationQuestion('<fg=cyan>Print snippet to screen?</> <fg=yellow>[no]</>: ', false);

        if(!$this->asker->ask($input, $output, $question))
            return;

        $this->printSnippet($output);
    }

    /**
     * @param OutputInterface $output
     */
    private function printSnippet(OutputInterface $output)
    {
        $filepath = $this->directory. '/'. $this->filename;
        $file = fopen($filepath, 'r');

        if($file === FALSE)
            throw new \RuntimeException('There was an error opening the file: '. $filepath);

        $snippet = '';

        for($l = 0; $l != $this->number_of_lines; ++$l)
            $snippet .= fgets($file);

        $output->writeln($snippet);
        fclose($file);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return bool|mixed
     */
    private function editFile(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Apply your changes.');
        $question = new ConfirmationQuestion('<fg=cyan>Save file?</> <fg=yellow>[yes]</>', true);

        if(!$this->asker->ask($input, $output, $question))
            return false;

        return json_decode($this->readFile(), true);
    }

    /**
     * @return string
     */
    private function readFile()
    {
        $filepath = $this->directory. '/'. $this->filename;
        $file = fopen($filepath, 'r');

        if($file === FALSE)
            throw new \RuntimeException('There was an error opening the file: '. $filepath);

        return file_get_contents($filepath, true);
    }

    /**
     * @param array $menu
     */
    private function writeToFile(array $menu)
    {
        $filepath = $this->directory . '/' . $this->filename;
        $file = fopen($filepath, 'w');

        if($file === FALSE)
            throw new \RuntimeException('There was an error opening the file: '. $filepath);

        fwrite($file, json_encode($menu, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
        fclose($file);
    }

    public function populateMenu(array $menu, $shop_name, $em)
    {
        $shop = new Shop();

        $shop
            ->setName($shop_name)
            ->setImageLocation($menu['logo']);

        $em->persist($shop);

        foreach($menu['products'] as $menu_entry)
        {
            $product = new Product();
            $product
                ->setImageLocation($menu_entry['image'])
                ->setName($menu_entry['title'])
                ->setDescription($menu_entry['description'])
                ->setPrice($menu_entry['price'])
                ->setShop($shop);

            $em->persist($product);
        }

        $em->flush();
    }
}