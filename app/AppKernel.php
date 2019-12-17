<?php
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new FOS\CKEditorBundle\FOSCKEditorBundle(),
            new BeSimple\SsoAuthBundle\BeSimpleSsoAuthBundle(),
            new Marca\HomeBundle\MarcaHomeBundle(),
            new Marca\UserBundle\MarcaUserBundle(),
            new Marca\CourseBundle\MarcaCourseBundle(),
            new Marca\JournalBundle\MarcaJournalBundle(),
            new Marca\ForumBundle\MarcaForumBundle(),
            new Marca\FileBundle\MarcaFileBundle(),
            new Marca\DocBundle\MarcaDocBundle(),
            new Marca\TagBundle\MarcaTagBundle(),
            new Marca\PortfolioBundle\MarcaPortfolioBundle(),
            new Marca\CalendarBundle\MarcaCalendarBundle(),
            new Marca\NoteBundle\MarcaNoteBundle(),
            new Marca\AdminBundle\MarcaAdminBundle(),
            new Marca\ResponseBundle\MarcaResponseBundle(),
            new Marca\AssessmentBundle\MarcaAssessmentBundle(),
            new Marca\AssignmentBundle\MarcaAssignmentBundle(),
            new Marca\GradebookBundle\MarcaGradebookBundle(),
        ];
        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            if ('dev' === $this->getEnvironment()) {
                $bundles[] = new Symfony\Bundle\WebServerBundle\WebServerBundle();
            }
        }
        return $bundles;
    }
    public function getRootDir()
    {
        return __DIR__;
    }
    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }
    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->setParameter('container.autowiring.strict_mode', true);
            $container->setParameter('container.dumper.inline_class_loader', true);
            $container->addObjectResource($this);
        });
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}

