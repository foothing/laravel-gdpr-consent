<?php namespace tests\Foothing\Laravel\Consent\Core;

use Foothing\Laravel\Consent\Core\ConsentManager;
use Foothing\Laravel\Consent\Core\EventManager;
use Foothing\Laravel\Consent\Models\Consent;
use Foothing\Laravel\Consent\Models\Treatment;
use Foothing\Laravel\Consent\Repositories\ConsentRepository;
use Mockery\MockInterface;
use tests\Foothing\Laravel\Consent\Mocks\User;

class ConsentManagerTest /*extends \PHPUnit_Framework_TestCase*/
{

    /**
     * @var MockInterface
     */
    protected $repository;

    /**
     * @var MockInterface
     */
    protected $events;

    /**
     * @var ConsentManager
     */
    protected $manager;

    public function setUp()
    {
        parent::setUp();

        $this->repository = \Mockery::mock(ConsentRepository::class);

        $this->events = \Mockery::mock(EventManager::class);

        $this->manager = new ConsentManager($this->repository, $this->events);
    }

    /**
     * @test
     */
    public function it_should_return_a_new_instance_if_consent_doesnt_exists()
    {
        $this
            ->repository
            ->shouldReceive('findConsentBySubjectAndTreatment')
            ->andReturn(null);

        $consent = $this->manager->getSubjectConsentByTreatment(new User(), new Treatment());

        $this->assertEquals(false, $consent->exists);
    }

    /**
     * @test
     */
    public function it_should_return_the_existing_consent_record()
    {
        $this
            ->repository
            ->shouldReceive('findConsentBySubjectAndTreatment')
            ->andReturn(new Consent(['subject_id' => 100, 'treatment_id' => 100]));

        $consent = $this->manager->getSubjectConsentByTreatment(new User(), new Treatment());

        $this->assertEquals(100, $consent->subject_id);
        $this->assertEquals(100, $consent->treatment_id);
    }

    /**
     * @test
     */
    public function it_should_invoke_the_grant_transaction()
    {
        /*$manager = \Mockery::mock(
            "Foothing\Laravel\Consent\Core\ConsentManager[grantConsentTransaction,getSubjectConsentByTreatment]",
            [$this->repository, $this->events]
        );

        $manager->shouldReceive('getSubjectConsentByTreatment')->andReturn(new Consent(['subject_id' => 100, 'treatment_id' => 100]));
        $manager->shouldReceive('grantConsentTransaction');

        $consent = $manager->grantConsent(new Treatment(), new User());*/
    }
}
